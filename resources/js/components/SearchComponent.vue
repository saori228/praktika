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
      
      <!-- Фильтры -->
      <div class="filters mb-6 md:mb-8">
        <!-- Фильтр по типу -->
        <div class="categories flex space-x-4 md:space-x-12 mb-4 md:mb-6 overflow-x-auto pb-2">
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
        
        <!-- Фильтр по дате -->
        <div class="date-filters bg-white p-4 rounded-lg shadow-md">
          <h3 class="text-lg font-semibold mb-3">Фильтр по дате</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
              <label for="date-from" class="block text-sm font-medium text-gray-700 mb-1">С даты</label>
              <input 
                type="date" 
                id="date-from"
                v-model="dateFrom" 
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                @change="validateDates"
              >
            </div>
            <div>
              <label for="date-to" class="block text-sm font-medium text-gray-700 mb-1">По дату</label>
              <input 
                type="date" 
                id="date-to"
                v-model="dateTo" 
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                @change="validateDates"
              >
            </div>
            <div class="flex gap-2">
              <button 
                @click="clearDateFilter" 
                class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors"
              >
                Очистить
              </button>
              <button 
                @click="setTodayFilter" 
                class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors"
              >
                Сегодня
              </button>
              <button 
                @click="setWeekFilter" 
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors"
              >
                Неделя
              </button>
            </div>
          </div>
          <div v-if="dateError" class="mt-2 text-red-600 text-sm">
            {{ dateError }}
          </div>
          <div v-if="dateFrom || dateTo" class="mt-2 text-sm text-gray-600">
            <span v-if="dateFrom && dateTo">
              Показаны события с {{ formatDisplayDate(dateFrom) }} по {{ formatDisplayDate(dateTo) }}
            </span>
            <span v-else-if="dateFrom">
              Показаны события с {{ formatDisplayDate(dateFrom) }}
            </span>
            <span v-else-if="dateTo">
              Показаны события до {{ formatDisplayDate(dateTo) }}
            </span>
          </div>
        </div>
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
      
      <div class="results-header flex justify-between items-center mb-4 md:mb-6">
        <h2 class="text-xl md:text-2xl font-bold">События от STADIUM</h2>
        <div class="text-sm text-gray-600">
          Найдено: {{ filteredEvents.length }} {{ getEventsWord(filteredEvents.length) }}
        </div>
      </div>
      
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
        <p v-if="hasActiveFilters" class="text-sm text-gray-400 mt-2">
          Попробуйте изменить фильтры поиска
        </p>
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
      dateFrom: '',
      dateTo: '',
      dateError: '',
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
      
      // Фильтр по поисковому запросу
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase();
        result = result.filter(event => 
          event.name.toLowerCase().includes(query) || 
          (event.artist && event.artist.name && event.artist.name.toLowerCase().includes(query))
        );
      }
      
      // Фильтр по типу события
      if (this.activeType) {
        result = result.filter(event => 
          event.event_type && event.event_type.name === this.activeType
        );
      }
      
      // Фильтр по дате
      if (this.dateFrom || this.dateTo) {
        result = result.filter(event => {
          if (!event.event_date) return false;
          
          const eventDate = new Date(event.event_date);
          const fromDate = this.dateFrom ? new Date(this.dateFrom) : null;
          const toDate = this.dateTo ? new Date(this.dateTo + 'T23:59:59') : null; // Включаем весь день
          
          if (fromDate && toDate) {
            return eventDate >= fromDate && eventDate <= toDate;
          } else if (fromDate) {
            return eventDate >= fromDate;
          } else if (toDate) {
            return eventDate <= toDate;
          }
          
          return true;
        });
      }
      
      return result;
    },
    currentSliderEvent() {
      return this.sliderEvents[this.currentSlideIndex] || {};
    },
    hasActiveFilters() {
      return this.searchQuery || this.activeType || this.dateFrom || this.dateTo;
    }
  },
  methods: {
    search() {
      console.log('Searching for:', this.searchQuery);
    },
    filterByType(type) {
      this.activeType = type;
    },
    validateDates() {
      this.dateError = '';
      
      if (this.dateFrom && this.dateTo) {
        const fromDate = new Date(this.dateFrom);
        const toDate = new Date(this.dateTo);
        
        if (fromDate > toDate) {
          this.dateError = 'Дата "С" не может быть позже даты "По"';
          return false;
        }
      }
      
      return true;
    },
    clearDateFilter() {
      this.dateFrom = '';
      this.dateTo = '';
      this.dateError = '';
    },
    setTodayFilter() {
      const today = new Date().toISOString().split('T')[0];
      this.dateFrom = today;
      this.dateTo = today;
      this.dateError = '';
    },
    setWeekFilter() {
      const today = new Date();
      const nextWeek = new Date();
      nextWeek.setDate(today.getDate() + 7);
      
      this.dateFrom = today.toISOString().split('T')[0];
      this.dateTo = nextWeek.toISOString().split('T')[0];
      this.dateError = '';
    },
    formatDisplayDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleDateString('ru-RU', { 
        day: 'numeric', 
        month: 'long', 
        year: 'numeric' 
      });
    },
    getEventsWord(count) {
      if (count % 10 === 1 && count % 100 !== 11) {
        return 'событие';
      } else if ([2, 3, 4].includes(count % 10) && ![12, 13, 14].includes(count % 100)) {
        return 'события';
      } else {
        return 'событий';
      }
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
      return date.toLocaleDateString('ru-RU', { 
        day: '2-digit', 
        month: '2-digit', 
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
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
      // Используем image_path из базы данных
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

.date-filters {
  border: 1px solid #e5e7eb;
}

.event-card {
  transition: transform 0.2s;
}

.event-card:hover {
  transform: translateY(-2px);
}
</style>
