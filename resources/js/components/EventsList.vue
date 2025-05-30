<template>
  <section id="events" class="bg-yellow-300 py-12 md:py-16">
    <div class="container mx-auto px-4">
      <!-- Мобильная версия -->
      <div class="md:hidden">
        <h2 class="text-2xl font-bold text-center mb-8">Карта мероприятий</h2>
        <div class="grid grid-cols-2 gap-6">
          <div class="map-card  rounded-lg overflow-hidden shadow-lg p-4">
            <h3 class="text-lg font-semibold mb-4 text-center">Концерты</h3>
            <img src="/images/map/concerts-map.jpg" alt="Карта концертов" class="w-full h-32 object-cover rounded-lg">
          </div>
          
          <div class="map-card rounded-lg overflow-hidden shadow-lg p-4">
            <h3 class="text-lg font-semibold mb-4 text-center">Театры</h3>
            <img src="/images/map/theaters-map.jpg" alt="Карта театров" class="w-full h-32 object-cover rounded-lg">
          </div>
          
          <div class="map-card  rounded-lg overflow-hidden shadow-lg p-4">
            <h3 class="text-lg font-semibold mb-4 text-center">Кинотеатры</h3>
            <img src="/images/map/cinemas-map.jpg" alt="Карта кинотеатров" class="w-full h-32 object-cover rounded-lg">
          </div>
          
          <div class="map-card  rounded-lg overflow-hidden shadow-lg p-4">
            <h3 class="text-lg font-semibold mb-4 text-center">Выставки</h3>
            <img src="/images/map/exhibitions-map.jpg" alt="Карта выставок" class="w-full h-32 object-cover rounded-lg">
          </div>
        </div>
      </div>
      
      <!-- Десктопная версия -->
      <div class="hidden md:block">
        <h2 class="text-4xl font-bold text-center mb-10">Карта мероприятий</h2>
        <div class="grid grid-cols-4 gap-8">
          <div class="map-card  rounded-lg overflow-hidden shadow-lg p-4">
            <h3 class="text-lg font-semibold mb-4 text-center">Концерты</h3>
            <img src="/images/map/concerts-map.jpg" alt="Карта концертов" class="w-full h-48 object-cover rounded-lg">
          </div>
          
          <div class="map-card  rounded-lg overflow-hidden shadow-lg p-4">
            <h3 class="text-lg font-semibold mb-4 text-center">Театры</h3>
            <img src="/images/map/theaters-map.jpg" alt="Карта театров" class="w-full h-48 object-cover rounded-lg">
          </div>
          
          <div class="map-card rounded-lg overflow-hidden shadow-lg p-4">
            <h3 class="text-lg font-semibold mb-4 text-center">Кинотеатры</h3>
            <img src="/images/map/cinemas-map.jpg" alt="Карта кинотеатров" class="w-full h-48 object-cover rounded-lg">
          </div>
          
          <div class="map-card rounded-lg overflow-hidden shadow-lg p-4">
            <h3 class="text-lg font-semibold mb-4 text-center">Выставки</h3>
            <img src="/images/map/exhibitions-map.jpg" alt="Карта выставок" class="w-full h-48 object-cover rounded-lg">
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  props: {
    events: {
      type: Array,
      default: () => []
    }
  }
}
</script>

<style scoped>
.map-card {
  transition: transform 0.3s;
}

.map-card:hover {
  transform: translateY(-5px);
}
</style>
