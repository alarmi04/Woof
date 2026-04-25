<script setup>
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
</script>

<template>
    <Header></Header>
    <section class="hero">
        <img
          src="/images/bulldog-1047518_640.jpg"
          alt="Perro"
          class="imagenPortada"
        />
        <div class="infoPortada">
          <h1>Encuentra a tu mejor amigo</h1>
          <p>
            Miles de perritos esperan un hogar lleno de amor. Adopta hoy y
            cambia dos vidas para siempre: la tuya y la suya.
          </p>
          <a href="./adopta.html" class="hero-btn">Ver perritos disponibles</a>
        </div>
    </section>

    <section class="sobre-nosotros">
        <img src="/images/perrera.jpg" alt="Perrera">
        <div class="infoSobreNosotros"> 
            <h1>Sobre Nosotros</h1>
            <p>
                Nuestra función es dar un hogar a esos perros que aun no lo tienen, 
                y que una vez encontrado su hogar sea el definitivo.
            </p>
            <p>
                En un estudio de Charleston Animal Society (EEUU) que analizó animales
                adoptados entre 2015-2019: de 9.996 perros adoptados, 
                el 16.3% fueron devueltos al refugio dentro de los primeros seis meses.
            </p>
            <p>
                Nosotros buscamos erradicar/disminuir este porcentaje. 
            </p>
        </div>
    </section>
    <Footer></Footer>
</template>

<style>
.hero {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: space-between;
  height: 80vh;
  padding: 4rem 2rem;
}

.imagenPortada {
  margin-left: 10vw;
  width: 400px;
  height: 400px;
  border: 2px solid #f2790f;
  border-radius: 50%;
  object-fit: cover;
  box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
}

.infoPortada {
  margin-right: 8vw;
  text-align: center;
}

.hero h1 {
  font-size: 3rem;
  color: #d97f11;
  margin-bottom: 1rem;
  font-weight: 700;
}

.hero p {
  font-size: 1.3rem;
  color: #555;
  max-width: 650px;
  margin-bottom: 2.5rem;
  line-height: 1.6;
}

.hero-btn {
  background-color: #f2790f;
  color: white;
  padding: 1rem 3rem;
  border-radius: 9999px;
  text-decoration: none;
  font-size: 1.2rem;
  font-weight: 600;
  transition: all 0.3s ease;
  display: inline-block;
}

.hero-btn:hover {
  background-color: #d97f11;
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(242, 121, 15, 0.4);
}

/* Sobre Nosotros */
.sobre-nosotros {
  display: flex;
  flex-direction: row;
  align-items: center;
  margin-bottom: 5rem;
  padding: 2rem;
  gap: 3rem;
}

.sobre-nosotros img {
  width: 400px;
  height: 400px;
  border-radius: 15px;
  object-fit: cover;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.infoSobreNosotros {
  flex: 1;
  padding-left: 2rem;
}

.infoSobreNosotros h1 {
  font-size: 2.5rem;
  color: #d97f11;
  text-align: left;
  margin-bottom: 1rem;
}

.infoSobreNosotros p {
  font-size: 1.1rem;
  color: #555;
  text-align: left;
  line-height: 1.8;
  margin-bottom: 1rem;
}
</style>