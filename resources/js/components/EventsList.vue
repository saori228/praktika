<template>
  <section id="events" class="bg-yellow-300 py-12 md:py-16">
    <div class="container mx-auto px-4">
      <!-- Мобильная версия -->
      <div class="md:hidden">
        <h2 class="text-2xl font-bold text-center mb-8">Карта мероприятий</h2>
        <div class="grid grid-cols-2 gap-6">
          <div v-for="(event, index) in events" :key="index" class="event-card">
            <img :src="event.image_path || '/images/events/event-placeholder.jpg'" :alt="event.name" class="rounded-lg w-full h-48 object-cover">
          </div>
        </div>
      </div>
      
      <!-- Десктопная версия -->
      <div class="hidden md:block">
        <h2 class="text-4xl font-bold text-center mb-10">Карта мероприятий</h2>
        <div class="grid grid-cols-4 gap-8">
          <div v-for="(event, index) in events" :key="index" class="event-card">
            <img :src="event.image_path || '/images/events/event-placeholder.jpg'" :alt="event.name" class="rounded-lg w-full h-64 object-cover">
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
      default: () => [
        { name: 'Event 1', image_path: '/images/events/event-placeholder.jpg' },
        { name: 'Event 2', image_path: '/images/events/event-placeholder.jpg' },
        { name: 'Event 3', image_path: '/images/events/event-placeholder.jpg' },
        { name: 'Event 4', image_path: '/images/events/event-placeholder.jpg' }
      ]
    }
  }
}
</script>

<style scoped>
.event-card {
  transition: transform 0.3s;
}

.event-card:hover {
  transform: scale(1.05);
}
</style>