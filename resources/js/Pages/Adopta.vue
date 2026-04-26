<script setup>
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';   

</script>

<template>
    <Header></Header>
    <section id="adopcion">
        <div class="adopcion-container">
            <h1>Encuentra a tu Compañero Perfecto</h1>
            <p class="adopcion-subtitle">Todos nuestros perritos estan esperando un hogar lleno de amor</p>

            <div class="filtros-container">

                <div class="filtro-grupo">
                    <label>Tamaño: </label>
                    <div class="filtro-botones">
                        <button class="filtro-btn active"  data-filtro="tamaño" data-valor="todos">Todos</button>
                        <button class="filtro-btn" data-filtro="tamaño" data-valor="pequeño">Pequeño</button>
                        <button class="filtro-btn" data-filtro="tamaño" data-valor="mediano">Mediano</button>
                        <button class="filtro-btn" data-filtro="tamaño" data-valor="grande">Grande</button>
                    </div>
                </div>

                <div class="filtro-grupo">
                    <label>Edad:</label>
                    <div class="filtro-botones">
                        <button class="filtro-btn active" data-filtro="edad" data-valor="todos">Todos</button>
                        <button class="filtro-btn" data-filtro="edad" data-valor="joven">Joven</button>
                        <button class="filtro-btn" data-filtro="edad" data-valor="adulto">Adulto</button>
                        <button class="filtro-btn" data-filtro="edad" data-valor="senior">Senior</button>
                    </div>
                </div>

                <div class="filtro-grupo">
                    <label>Sexo:</label>
                    <div class="filtro-botones">
                        <button class="filtro-btn active" data-filtro="sexo" data-valor="todos">Todos</button>
                        <button class="filtro-btn" data-filtro="sexo" data-valor="macho">Macho</button>
                        <button class="filtro-btn" data-filtro="sexo" data-valor="hembra">Hembra</button>
                    </div>
                </div>
            </div>
      
            <div class="perros-grid" id="perrosGrid"></div>
            <div class="no-resultados" id="noResultados" style="display: none">
                <p>😔 No encontramos perritos con esos filtros.</p>
            </div>
        </div>
    </section>
    <Footer></Footer>
</template>

<style>
    #adopcion,
    #detalle-perro,
    #enviar-perro {
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
    }

    .adopcion-container,
    .detalle-container,
    .enviar-container {
    max-width: 1400px;
    margin: 0 auto;
    }

    .adopcion-container h1,
    .enviar-header h1 {
    text-align: center;
    font-size: 3rem;
    color: #d97f11;
    margin-bottom: 1rem;
    font-weight: 700;
    }

    .adopcion-subtitle,
    .enviar-header p {
    text-align: center;
    font-size: 1.2rem;
    color: #666;
    margin-bottom: 3rem;
    line-height: 1.6;
    }

    /* Filtros */
    .filtros-container {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-bottom: 3rem;
    }

    .filtro-grupo {
    margin-bottom: 1.5rem;
    }

    .filtro-grupo:last-child {
    margin-bottom: 0;
    }

    .filtro-grupo label {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.8rem;
    font-size: 1.1rem;
    }

    .filtro-botones {
    display: flex;
    flex-wrap: wrap;
    gap: 0.8rem;
    }

    .filtro-btn {
    padding: 0.7rem 1.5rem;
    border: 2px solid #e0e0e0;
    background: white;
    color: #666;
    border-radius: 25px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
    }

    .filtro-btn:hover {
    border-color: #f2790f;
    color: #f2790f;
    transform: translateY(-2px);
    }

    .filtro-btn.active {
    background: #f2790f;
    color: white;
    border-color: #f2790f;
    }

    /* Grid de perros */
    .perros-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
    }

    .perro-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    }

    .perro-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(242, 121, 15, 0.2);
    }

    .perro-imagen {
    width: 100%;
    height: 300px;
    object-fit: cover;
    }

    .perro-info {
    padding: 1.5rem;
    }

    .perro-nombre {
    font-size: 1.5rem;
    font-weight: 700;
    color: #d97f11;
    margin-bottom: 0.5rem;
    }

    .perro-detalles {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    }

    .perro-tag,
    .personalidad-tag {
    background: #f2d888;
    color: #d97f11;
    padding: 0.4rem 0.8rem;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 600;
    }

    .perro-descripcion {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
    }

    .perro-btn {
    width: 100%;
    padding: 0.9rem;
    background: #f2790f;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    }

    .perro-btn:hover {
    background: #d97f11;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(242, 121, 15, 0.3);
    }

    .no-resultados {
    text-align: center;
    padding: 3rem;
    background: white;
    border-radius: 20px;
    margin-top: 2rem;
    }

    .no-resultados p {
    font-size: 1.2rem;
    color: #666;
    }

</style>