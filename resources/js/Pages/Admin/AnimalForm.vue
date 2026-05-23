<script setup>
import { computed, watch, ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';

const page = usePage();
const animal = computed(() => page.props.animal);
const accion = computed(() => page.props.accion);
const imagenPreview = ref(null);

const form = useForm({
    Nombre: '',
    Raza: '',
    Genero: '',
    Edad: '',
    Tamano: 'mediano',
    Estado: 'disponible',
    Descripcion: '',
    Nivel_actividad: 3,
    Vivienda_recomendada: '',
    Apto_ninos: false,
    Experiencia_requerida: false,
    Tiempo_requerido: null,
    Imagen: null,
});

// Cuando se carga el animal (modo edición), actualizar el formulario
watch(animal, (newAnimal) => {
    if (newAnimal) {
        form.Nombre = newAnimal.Nombre || '';
        form.Raza = newAnimal.Raza || '';
        form.Genero = newAnimal.Genero || '';
        form.Edad = newAnimal.Edad || '';
        form.Tamano = newAnimal.Tamano || 'mediano';
        form.Estado = String(newAnimal.Estado || 'disponible').toLowerCase();
        form.Descripcion = newAnimal.Descripcion || '';
        form.Nivel_actividad = parseInt(newAnimal.Nivel_actividad) || 3;
        form.Vivienda_recomendada = newAnimal.Vivienda_recomendada || '';
        form.Apto_ninos = Boolean(newAnimal.Apto_ninos);
        form.Experiencia_requerida = Boolean(newAnimal.Experiencia_requerida);
        form.Tiempo_requerido = newAnimal.Tiempo_requerido ? parseInt(newAnimal.Tiempo_requerido) : null;
        
        // Cargar imagen existente si hay
        if (newAnimal.Imagen) {
            imagenPreview.value = newAnimal.Imagen;
        }
    }
}, { immediate: true, deep: true });

const handleImagenChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.Imagen = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagenPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    if (animal.value) {
        form.transform((data) => ({
            ...data,
            _method: 'put',
        })).post(route('admin.animales.update', animal.value.idAnimal), {
            onSuccess: () => {
                alert('Perro actualizado correctamente');
            },
        });
    } else {
        form.post(route('admin.animales.store'), {
            onSuccess: () => {
                alert('Perro creado correctamente');
                window.location.href = '/admin/animales';
            },
        });
    }
};
</script>

<template>
    <Header />
    <div class="form-container">
        <div class="form-header">
            <h1>{{ accion === 'crear' ? 'Crear Nuevo Perro' : 'Editar Ficha de ' + animal.Nombre }}</h1>
        </div>

        <div class="form-wrapper">
            <form @submit.prevent="submit">
                <!-- Nombre -->
                <div class="form-group">
                    <label for="Nombre">Nombre del Perro *</label>
                    <input 
                        id="Nombre"
                        v-model="form.Nombre" 
                        type="text" 
                        placeholder="Ej: Max"
                        required
                    >
                    <div v-if="form.errors.Nombre" class="error">{{ form.errors.Nombre }}</div>
                </div>

                <!-- Raza -->
                <div class="form-group">
                    <label for="Raza">Raza *</label>
                    <input 
                        id="Raza"
                        v-model="form.Raza" 
                        type="text" 
                        placeholder="Ej: Labrador"
                        required
                    >
                    <div v-if="form.errors.Raza" class="error">{{ form.errors.Raza }}</div>
                </div>

                <div class="form-row">
                    <!-- Género -->
                    <div class="form-group">
                        <label for="Genero">Género *</label>
                        <select id="Genero" v-model="form.Genero" required>
                            <option value="">Selecciona género</option>
                            <option value="Macho">Macho</option>
                            <option value="Hembra">Hembra</option>
                        </select>
                        <div v-if="form.errors.Genero" class="error">{{ form.errors.Genero }}</div>
                    </div>

                    <!-- Edad -->
                    <div class="form-group">
                        <label for="Edad">Edad *</label>
                        <select id="Edad" v-model="form.Edad" required>
                            <option value="">Selecciona edad</option>
                            <option value="joven">Joven</option>
                            <option value="adulto">Adulto</option>
                            <option value="senior">Senior</option>
                        </select>
                        <div v-if="form.errors.Edad" class="error">{{ form.errors.Edad }}</div>
                    </div>
                </div>

                <div class="form-row">
                    <!-- Tamaño -->
                    <div class="form-group">
                        <label for="Tamano">Tamaño *</label>
                        <select id="Tamano" v-model="form.Tamano" required>
                            <option value="pequeño">Pequeño</option>
                            <option value="mediano">Mediano</option>
                            <option value="grande">Grande</option>
                        </select>
                        <div v-if="form.errors.Tamano" class="error">{{ form.errors.Tamano }}</div>
                    </div>

                    <!-- Estado -->
                    <div class="form-group">
                        <label for="Estado">Estado *</label>
                        <select id="Estado" v-model="form.Estado" required>
                            <option value="">Selecciona estado</option>
                            <option value="disponible">Disponible</option>
                            <option value="adoptado">Adoptado</option>
                            <option value="pendiente">Pendiente</option>
                        </select>
                        <div v-if="form.errors.Estado" class="error">{{ form.errors.Estado }}</div>
                    </div>
                </div>

                <!-- Imagen -->
                <div class="form-group">
                    <label for="Imagen">Imagen del Perro</label>
                    <input 
                        id="Imagen"
                        type="file"
                        accept="image/*"
                        @change="handleImagenChange"
                    >
                    <small>Formatos: JPG, PNG, GIF (máx 2MB)</small>
                    <div v-if="form.errors.Imagen" class="error">{{ form.errors.Imagen }}</div>
                    <div v-if="imagenPreview" class="imagen-preview">
                        <img :src="imagenPreview" :alt="form.Nombre || 'Preview'">
                    </div>
                </div>

                <!-- Descripción -->
                <div class="form-group">
                    <label for="Descripcion">Descripción *</label>
                    <textarea 
                        id="Descripcion"
                        v-model="form.Descripcion" 
                        rows="5"
                        placeholder="Describe al perro, su personalidad, características especiales..."
                        required
                    ></textarea>
                    <div v-if="form.errors.Descripcion" class="error">{{ form.errors.Descripcion }}</div>
                </div>

                <div class="form-row">
                    <!-- Nivel de Actividad -->
                    <div class="form-group">
                        <label for="Nivel_actividad">Nivel de Actividad (1-5) *</label>
                        <input 
                            id="Nivel_actividad"
                            v-model.number="form.Nivel_actividad" 
                            type="number" 
                            min="1"
                            max="5"
                            required
                        >
                        <small>1: Bajo | 3: Medio | 5: Alto</small>
                        <div v-if="form.errors.Nivel_actividad" class="error">{{ form.errors.Nivel_actividad }}</div>
                    </div>

                    <!-- Tiempo Requerido -->
                    <div class="form-group">
                        <label for="Tiempo_requerido">Tiempo Requerido (horas/día)</label>
                        <input 
                            id="Tiempo_requerido"
                            v-model.number="form.Tiempo_requerido" 
                            type="number"
                            min="0"
                        >
                        <div v-if="form.errors.Tiempo_requerido" class="error">{{ form.errors.Tiempo_requerido }}</div>
                    </div>
                </div>

                <!-- Vivienda Recomendada -->
                <div class="form-group">
                    <label for="Vivienda_recomendada">Vivienda Recomendada *</label>
                    <select id="Vivienda_recomendada" v-model="form.Vivienda_recomendada" required>
                        <option value="">Selecciona vivienda</option>
                        <option value="piso">Piso</option>
                        <option value="casa">Casa</option>
                        <option value="casa_con_jardin">Casa con Jardín</option>
                    </select>
                    <div v-if="form.errors.Vivienda_recomendada" class="error">{{ form.errors.Vivienda_recomendada }}</div>
                </div>

                <div class="form-row checkboxes">
                    <!-- Apto Niños -->
                    <div class="form-group checkbox">
                        <label for="Apto_ninos">
                            <input 
                                id="Apto_ninos"
                                v-model="form.Apto_ninos" 
                                type="checkbox"
                            >
                            Apto para niños
                        </label>
                        <div v-if="form.errors.Apto_ninos" class="error">{{ form.errors.Apto_ninos }}</div>
                    </div>

                    <!-- Experiencia Requerida -->
                    <div class="form-group checkbox">
                        <label for="Experiencia_requerida">
                            <input 
                                id="Experiencia_requerida"
                                v-model="form.Experiencia_requerida" 
                                type="checkbox"
                            >
                            Requiere experiencia
                        </label>
                        <div v-if="form.errors.Experiencia_requerida" class="error">{{ form.errors.Experiencia_requerida }}</div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="form-actions">
                    <button type="submit" :disabled="form.processing" class="btn-submit">
                        {{ form.processing ? 'Guardando...' : (accion === 'crear' ? 'Crear Perro' : 'Guardar Cambios') }}
                    </button>
                    <a href="/admin/animales" class="btn-cancel">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
.form-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 3rem 2rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

.form-header {
    text-align: center;
    margin-bottom: 3rem;
}

.form-header h1 {
    font-size: 2rem;
    color: #d97f11;
    margin: 0;
    font-weight: 700;
}

.form-wrapper {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 0.8rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1rem;
    font-family: inherit;
    transition: all 0.2s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #f97316;
    box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
}

.form-group input[type="file"] {
    padding: 0.5rem;
    cursor: pointer;
}

.imagen-preview {
    margin-top: 1rem;
    border-radius: 8px;
    overflow: hidden;
    max-width: 300px;
}

.imagen-preview img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.form-group small {
    border-color: #d97f11;
    box-shadow: 0 0 0 3px rgba(217, 127, 17, 0.1);
}

.form-group small {
    font-size: 0.85rem;
    color: #999;
    margin-top: 0.3rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.form-row.checkboxes {
    grid-template-columns: auto auto;
    gap: 2rem;
}

.form-group.checkbox {
    flex-direction: row;
    align-items: center;
}

.form-group.checkbox input {
    width: auto;
    margin-right: 0.5rem;
    cursor: pointer;
}

.form-group.checkbox label {
    margin: 0;
    cursor: pointer;
    font-weight: 500;
}

.error {
    color: #ef4444;
    font-size: 0.85rem;
    margin-top: 0.3rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    justify-content: center;
}

.btn-submit {
    flex: 1;
    padding: 1rem;
    background: linear-gradient(to right, #f97316, #ea580c);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-submit:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(234, 88, 12, 0.4);
}

.btn-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-cancel {
    flex: 1;
    padding: 1rem;
    background: #d97f11;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-cancel:hover {
    background: #b86000;
}

@media (max-width: 768px) {
    .form-container {
        padding: 1.5rem 1rem;
    }

    .form-header h1 {
        font-size: 1.5rem;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
    }
}
</style>
