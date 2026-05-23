# ==============================================================================
# FICHERO: python_logic/recomendar.py
# AUTOR: Alberto
# FECHA CREACIÓN: 2026-04-25
# FUNCIÓN: Motor de recomendación inteligente. Procesa los datos del adoptante
#          y calcula la compatibilidad con los perros para dar el mejor match.
# ==============================================================================

import sys
import json

# ------------------------------------------------------------------------------
# FUNCIÓN: calcular_puntuacion
# AUTOR: Alberto
# FECHA CREACIÓN: 2026-04-25
# DESCRIPCIÓN: Evalúa y puntúa las variables de perfil cruzado del perro y usuario.
# ------------------------------------------------------------------------------
def calcular_puntuacion(usuario, perro):
    """
    Calcula la compatibilidad entre un usuario y un perro basándose en varios factores.
    
    Args:
        usuario (dict): Diccionario con los datos del perfil del usuario.
        perro (dict): Diccionario con los datos del animal.
        
    Returns:
        tuple: (puntuacion_total, razon_del_match)
    """
    puntuacion = 0
    razones = []
    
    # 1. Vivienda y Tamaño
    vivienda_usuario = usuario.get('Tipo_vivienda')
    tamano_perro = perro.get('Tamano')
    
    if vivienda_usuario == 'piso':
        if tamano_perro == 'pequeño':
            puntuacion += 20
            razones.append("Su tamaño pequeño es ideal para tu piso.")
        elif tamano_perro == 'grande':
            puntuacion -= 10
    elif vivienda_usuario == 'casa_con_jardin':
        puntuacion += 15
        razones.append("Tienes el espacio perfecto para que corra.")
        
    # 2. Nivel de actividad
    actividad_usuario = usuario.get('Nivel_actividad')
    actividad_perro = perro.get('Nivel_actividad')
    
    if actividad_usuario == actividad_perro:
        puntuacion += 25
        razones.append("Vuestro nivel de energía es idéntico.")
    elif abs(int(actividad_usuario or 0) - int(actividad_perro or 0)) <= 1:
        puntuacion += 10
        
    # 3. Niños
    hijos = usuario.get('Numero_hijos', 0)
    apto_ninos = perro.get('Apto_ninos')
    
    if hijos > 0:
        if apto_ninos:
            puntuacion += 30
            razones.append("Es excelente con niños, perfecto para tu familia.")
        else:
            puntuacion -= 50 # Penalización crítica
    
    # 4. Experiencia
    exp_usuario = usuario.get('Experiencia_mascotas', 0)
    exp_req = perro.get('Experiencia_requerida')
    
    if exp_req and exp_usuario == 0:
        puntuacion -= 20
    elif exp_usuario >= 1:
        puntuacion += 10
        
    # 5. Tiempo disponible
    tiempo_u = usuario.get('Tiempo_disponible', 0)
    tiempo_p = perro.get('Tiempo_requerido', 0)
    
    if tiempo_u >= tiempo_p:
        puntuacion += 15
        razones.append("Dispones del tiempo que este perro necesita.")
    else:
        puntuacion -= 15

    # Construir la razón final (máximo 2 razones para no saturar)
    razon_final = " ".join(razones[:2]) if razones else "Un compañero equilibrado para ti."
    
    return puntuacion, razon_final

# ------------------------------------------------------------------------------
# FUNCIÓN: main
# AUTOR: Alberto
# FECHA CREACIÓN: 2026-04-25
# DESCRIPCIÓN: Lee el JSON de stdin, calcula afinidades y escribe el top 3 en stdout.
# ------------------------------------------------------------------------------
def main():
    """
    Punto de entrada principal del script.
    Lee los datos en formato JSON desde stdin y devuelve las recomendaciones en JSON.
    """
    try:
        # Leer datos de la entrada estándar (enviados desde PHP)
        datos_entrada = json.load(sys.stdin)
        usuario = datos_entrada.get('usuario', {})
        perros = datos_entrada.get('perros', [])
        
        resultados = []
        for perro in perros:
            puntuacion, razon = calcular_puntuacion(usuario, perro)
            resultados.append({
                'id': perro.get('idAnimal'),
                'score': puntuacion,
                'reason': razon
            })
            
        # Ordenar por puntuación descendente
        resultados.sort(key=lambda x: x['score'], reverse=True)
        
        # Devolver los 3 mejores con sus razones
        mejores_resultados = resultados[:3]
        
        # Imprimir resultado en formato JSON para que PHP lo capture
        print(json.dumps(mejores_resultados))
        
    except Exception as e:
        # En caso de error, devolver un JSON con la descripción del fallo
        print(json.dumps({"error": str(e)}))

if __name__ == "__main__":
    main()
