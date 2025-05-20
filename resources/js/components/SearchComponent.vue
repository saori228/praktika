<template>
  <div class="search-page py-6 md:py-8">
    <div class="container mx-auto px-4">
      <div class="search-bar mb-6 md:mb-8">
        <form @submit.prevent="search">
          <input 
            type="text" 
            v-model="searchQuery" 
            placeholder="Событие, персона" 
            class="w-full p-3 md:p-4 border rounded-lg text-base md:text-lg"
          >
        </form>
      </div>
      
      <div class="categories flex space-x-4 md:space-x-12 mb-6 md:mb-8 overflow-x-auto pb-2">
        <button 
          @click="filterByType('concert')" 
          :class="{ 'font-bold': activeType === 'concert' }"
          class="text-base md:text-xl whitespace-nowrap hover:text-blue-600 transition-colors"
        >
          Концерты
        </button>
        <button 
          @click="filterByType('theater')" 
          :class="{ 'font-bold': activeType === 'theater' }"
          class="text-base md:text-xl whitespace-nowrap hover:text-blue-600 transition-colors"
        >
          Театры
        </button>
        <button 
          @click="filterByType('movie')" 
          :class="{ 'font-bold': activeType === 'movie' }"
          class="text-base md:text-xl whitespace-nowrap hover:text-blue-600 transition-colors"
        >
          Кинофильмы
        </button>
      </div>
      
      <div class="search-slider mb-8 md:mb-12">
        <div class="relative">
          <div class="slider-content h-48 md:h-64 bg-gray-200 rounded-lg overflow-hidden">
            <div 
              v-for="(slide, index) in sliderEvents" 
              :key="index" 
              :class="{'active': currentSlideIndex === index}"
              class="search-slider-item"
            >
              <a :href="`/events/${slide.id}/booking`">
                <img :src="slide.slider_image || slide.image_path || '/images/slider/slider-placeholder.jpg'" :alt="slide.name" class="w-full h-full object-cover">
                <div class="search-slider-info">
                  {{ slide.name }}
                </div>
              </a>
            </div>
          </div>
          
          <div class="search-slider-controls">
            <button @click="prevSlide" class="search-slider-control">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            
            <button @click="nextSlide" class="search-slider-control">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
          </div>
        </div>
      </div>
      
      <h2 class="text-xl md:text-2xl font-bold mb-4 md:mb-6">События от STADIUM</h2>
      
      <div class="events-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-8">
        <a 
          v-for="event in filteredEvents" 
          :key="event.id" 
          :href="`/events/${event.id}/booking`" 
          class="event-card bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow"
        >
          <img :src="event.image_path || '/images/events/event-placeholder.jpg'" :alt="event.name" class="w-full h-36 md:h-48 object-cover">
          <div class="p-3 md:p-4">
            <h3 class="text-base md:text-lg font-semibold">{{ event.name }}</h3>
          </div>
        </a>
      </div>
      
      <div v-if="filteredEvents.length === 0" class="text-center py-8 md:py-12">
        <p class="text-lg md:text-xl text-gray-500">Мероприятия не найдены</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    initialEvents: {
      type: Array,
      default: () => []
    },
    initialSliderEvents: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      searchQuery: '',
      activeType: null,
      events: this.initialEvents,
      sliderEvents: this.initialSliderEvents.length > 0 ? this.initialSliderEvents : this.initialEvents.slice(0, 4),
      currentSlideIndex: 0,
      sliderInterval: null
    }
  },
  computed: {
    filteredEvents() {
      let result = this.events;
      
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase();
        result = result.filter(event => 
          event.name.toLowerCase().includes(query) || 
          (event.artist && event.artist.name.toLowerCase().includes(query))
        );
      }
      
      if (this.activeType) {
        result = result.filter(event => event.event_type.name === this.activeType);
      }
      
      return result;
    }
  },
  methods: {
    search() {
      // Можно добавить дополнительную логику поиска, например, запрос на сервер
      console.log('Searching for:', this.searchQuery);
    },
    filterByType(type) {
      this.activeType = this.activeType === type ? null : type;
    },
    nextSlide() {
      this.currentSlideIndex = (this.currentSlideIndex + 1) % this.sliderEvents.length;
    },
    prevSlide() {
      this.currentSlideIndex = (this.currentSlideIndex - 1 + this.sliderEvents.length) % this.sliderEvents.length;
    },
    startSliderInterval() {
      this.sliderInterval = setInterval(() => {
        this.nextSlide();
      }, 10000);
    }
  },
  mounted() {
    if (this.sliderEvents.length > 0) {
      this.startSliderInterval();
    }
  },
  beforeUnmount() {
    if (this.sliderInterval) {
      clearInterval(this.sliderInterval);
    }
  }
}
</script>

<style scoped>
.search-slider-item {
  position: absolute;
  width: 100%;
  height: 100%;
  opacity: 0;
  transition: opacity 0.5s ease-in-out;
}

.search-slider-item.active {
  opacity: 1;
}

.search-slider-controls {
  position: absolute;
  bottom: 10px;
  right: 10px;
  display: flex;
  gap: 10px;
}

.search-slider-control {
  width: 40px;
  height: 40px;
  background-color: rgba(255, 255, 255, 0.7);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background-color 0.3s;
}

.search-slider-control:hover {
  background-color: rgba(255, 255, 255, 0.9);
}

.search-slider-info {
  position: absolute;
  bottom: 10px;
  left: 10px;
  background-color: rgba(0, 0, 0, 0.7);
  color: white;
  padding: 8px 12px;
  border-radius: 4px;
}
</style>