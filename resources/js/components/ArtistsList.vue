<template>
  <section id="artists" class="bg-red-500 py-12 md:py-16">
    <div class="container mx-auto px-4">
      <!-- Мобильная версия -->
      <div class="md:hidden">
        <h2 class="text-2xl font-bold text-white text-center mb-8">Артисты</h2>
        <div class="grid grid-cols-2 gap-6">
          <a v-for="artist in artists" :key="artist.id" :href="`/artists/${artist.id}`" class="artist-card">
            <img :src="artist.image_path || '/images/artists/artist-placeholder.jpg'" :alt="artist.name" class="rounded-lg w-full h-48 object-cover">
          </a>
        </div>
      </div>
      
      <!-- Десктопная версия -->
      <div class="hidden md:block">
        <h2 class="text-4xl font-bold text-white text-center mb-10">Артисты</h2>
        <div class="grid grid-cols-4 gap-8">
          <a v-for="artist in artists" :key="artist.id" :href="`/artists/${artist.id}`" class="artist-card">
            <img :src="artist.image_path || '/images/artists/artist-placeholder.jpg'" :alt="artist.name" class="rounded-lg w-full h-64 object-cover">
          </a>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  props: {
    artists: {
      type: Array,
      required: true
    }
  }
}
</script>

<style scoped>
.artist-card {
  transition: transform 0.3s;
}

.artist-card:hover {
  transform: scale(1.05);
}
</style>