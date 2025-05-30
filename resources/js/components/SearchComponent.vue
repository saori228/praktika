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
          @click="filterByType(null)" 
          :class="{ 'font-bold': activeType === null }"
          class="text-base md:text-xl whitespace-nowrap hover:text-blue-600 transition-colors"
        >
          Все
        </button>
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
      
      <div class="search-slider mb-8 md:mb-12" v-if="sliderEvents.length > 0">
        <div class="relative">
          <div class="slider-content h-48 md:h-64 bg-gray-200 rounded-lg overflow-hidden">
            <!-- Одна динамическая ссылка, которая меняется с текущим слайдом -->
            <a :href="`/events/${currentSliderEvent.id}`" class="block w-full h-full">
              <div 
                v-for="(slide, index) in sliderEvents" 
                :key="`slider-${slide.id}`" 
                :class="{'active': currentSlideIndex === index}"
                class="search-slider-item"
              >
                <img :src="getSliderImageByEvent(slide)" :alt="slide.name" class="w-full h-full object-cover">
                <div class="search-slider-info">
                  {{ slide.name }}
                </div>
              </div>
            </a>
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
          
          <!-- Индикаторы слайдов -->
          <div class="slider-indicators">
            <button 
              v-for="(slide, index) in sliderEvents" 
              :key="`indicator-${slide.id}`"
              @click="goToSlide(index)"
              :class="{'active': currentSlideIndex === index}"
              class="slider-indicator"
            ></button>
          </div>
        </div>
      </div>
      
      <h2 class="text-xl md:text-2xl font-bold mb-4 md:mb-6">События от STADIUM</h2>
      
      <div v-if="filteredEvents.length > 0" class="events-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-8">
        <a 
          v-for="event in filteredEvents" 
          :key="`event-${event.id}`" 
          :href="`/events/${event.id}`" 
          class="event-card bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow"
        >
          <img :src="getEventImage(event)" :alt="event.name" class="w-full h-36 md:h-48 object-cover">
          <div class="p-3 md:p-4">
            <h3 class="text-base md:text-lg font-semibold">{{ event.name }}</h3>
            <p class="text-sm text-gray-600">{{ formatDate(event.event_date) }}</p>
            <p v-if="event.artist" class="text-sm text-gray-600">{{ event.artist.name }}</p>
          </div>
        </a>
      </div>
      
      <div v-else class="text-center py-8 md:py-12">
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
    },
    eventTypes: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      searchQuery: '',
      activeType: null,
      events: [],
      sliderEvents: [],
      currentSlideIndex: 0,
      sliderInterval: null,
      loading: false,
      error: null
    }
  },
  computed: {
    filteredEvents() {
      let result = this.events;
      
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase();
        result = result.filter(event => 
          event.name.toLowerCase().includes(query) || 
          (event.artist && event.artist.name && event.artist.name.toLowerCase().includes(query))
        );
      }
      
      if (this.activeType) {
        result = result.filter(event => 
          event.event_type && event.event_type.name === this.activeType
        );
      }
      
      return result;
    },
    currentSliderEvent() {
      return this.sliderEvents[this.currentSlideIndex] || {};
    }
  },
  methods: {
    search() {
      console.log('Searching for:', this.searchQuery);
    },
    filterByType(type) {
      this.activeType = type;
    },
    nextSlide() {
      if (this.sliderEvents.length === 0) return;
      this.currentSlideIndex = (this.currentSlideIndex + 1) % this.sliderEvents.length;
    },
    prevSlide() {
      if (this.sliderEvents.length === 0) return;
      this.currentSlideIndex = (this.currentSlideIndex - 1 + this.sliderEvents.length) % this.sliderEvents.length;
    },
    goToSlide(index) {
      this.currentSlideIndex = index;
    },
    startSliderInterval() {
      if (this.sliderEvents.length > 0) {
        this.sliderInterval = setInterval(() => {
          this.nextSlide();
        }, 5000);
      }
    },
    formatDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleDateString('ru-RU', { day: '2-digit', month: '2-digit', year: 'numeric' });
    },
    getSliderImageByEvent(event) {
      if (!event) return '/images/slider/slide-default.jpg';
      
      if (event.name.includes('OFFSET')) {
        return '/images/slider/offset-slide.jpg';
      } else if (event.name.includes('ОЧИ')) {
        return '/images/slider/ochi-slide.jpg';
      } else if (event.name.includes('Последняя сказка')) {
        return '/images/slider/theater-slide.jpg';
      } else if (event.name.includes('Хаски')) {
        return '/images/slider/husky-slide.jpg';
      }
      
      return '/images/slider/slide-default.jpg';
    },
    getEventImage(event) {
      // ГЛАВНОЕ ИЗМЕНЕНИЕ: Сначала проверяем image_path из базы данных
      if (event.image_path) {
        return event.image_path;
      }
      
      // Fallback: если image_path пустой, используем логику по названию
      if (event.name.includes('Три дня дождя')) {
        return '/images/events/tri-dnya-dozhdya.jpg';
      } else if (event.name.includes('OG Buda')) {
        return '/images/events/og-buda.jpg';
      } else if (event.name.includes('SQWOZ BAB')) {
        return '/images/events/sqwoz-bab.jpg';
      } else if (event.name.includes('Хаски')) {
        return '/images/events/husky.jpg';
      } else if (event.name.includes('OFFSET')) {
        return '/images/events/offset.jpg';
      } else if (event.name.includes('Последняя сказка')) {
        return '/images/events/theater.jpg';
      } else if (event.name.includes('ОЧИ')) {
        return '/images/events/movie.jpg';
      } else if (event.event_type) {
        // Fallback изображения по типу события
        if (event.event_type.name === 'concert') {
          return '/images/events/concert-default.jpg';
        } else if (event.event_type.name === 'theater') {
          return '/images/events/theater-default.jpg';
        } else if (event.event_type.name === 'movie') {
          return '/images/events/movie-default.jpg';
        }
      }
      
      return '/images/events/event-default.jpg';
    },
    initializeData() {
      if (this.initialEvents && this.initialEvents.length > 0) {
        this.events = this.initialEvents;
      } else {
        this.fetchEvents();
      }
      
      if (this.initialSliderEvents && this.initialSliderEvents.length > 0) {
        console.log('Initial slider events:', this.initialSliderEvents);
        this.sliderEvents = this.initialSliderEvents;
      } else {
        this.fetchSliderEvents();
      }
    },
    async fetchEvents() {
      this.loading = true;
      this.error = null;
      
      try {
        const response = await fetch('/api/events');
        if (!response.ok) {
          throw new Error('Ошибка при загрузке событий');
        }
        
        const data = await response.json();
        this.events = data.events || [];
      } catch (error) {
        console.error('Error fetching events:', error);
        this.error = error.message;
      } finally {
        this.loading = false;
      }
    },
    async fetchSliderEvents() {
      try {
        const response = await fetch('/api/slider-events');
        if (!response.ok) {
          throw new Error('Ошибка при загрузке слайдер-событий');
        }
        
        const data = await response.json();
        console.log('Fetched slider events:', data.events);
        this.sliderEvents = data.events || [];
      } catch (error) {
        console.error('Error fetching slider events:', error);
      }
    }
  },
  mounted() {
    console.log('SearchComponent mounted');
    this.initializeData();
    this.startSliderInterval();
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
  z-index: 10;
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

.slider-indicators {
  position: absolute;
  bottom: 10px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 8px;
}

.slider-indicator {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background-color: rgba(255, 255, 255, 0.5);
  cursor: pointer;
  transition: background-color 0.3s;
}

.slider-indicator.active {
  background-color: rgba(255, 255, 255, 1);
}

.slider-indicator:hover {
  background-color: rgba(255, 255, 255, 0.8);
}
</style>
