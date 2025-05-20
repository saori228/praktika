<template>
  <div class="artist-detail">
    <div class="artist-info mb-8">
      <h1 class="text-2xl md:text-3xl font-bold mb-4">{{ artist.name }}</h1>
      <div class="md:w-2/3 mx-auto">
        <p class="text-gray-700 mb-4">{{ artist.description }}</p>
      </div>
    </div>
    
    <div class="event-info mb-8" v-if="event">
      <div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl mx-auto">
        <h2 class="text-xl font-bold mb-4">Ближайшее мероприятие</h2>
        <div class="flex flex-col md:flex-row">
          <div class="md:w-1/3 mb-4 md:mb-0">
            <img :src="event.image_path || '/images/events/event-placeholder.jpg'" :alt="event.name" class="rounded-lg w-full h-48 object-cover">
          </div>
          <div class="md:w-2/3 md:pl-6">
            <h3 class="text-lg font-semibold mb-2">{{ event.name }}</h3>
            <p class="text-gray-600 mb-2">{{ formatDate(event.event_date) }}</p>
            <p class="text-gray-700 mb-4">{{ event.description }}</p>
            <a :href="`/events/${event.id}/booking`" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
              Купить билет
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    artist: {
      type: Object,
      required: true
    },
    event: {
      type: Object,
      default: null
    }
  },
  methods: {
    formatDate(dateString) {
      if (!dateString) return '';
      
      const date = new Date(dateString);
      return date.toLocaleDateString('ru-RU', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
      });
    }
  }
}
</script>