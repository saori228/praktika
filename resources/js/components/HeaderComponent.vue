<template>
  <header :class="headerClass">
    <div class="container mx-auto px-4 py-4">
      <div class="flex justify-between items-center">
        <div class="logo">
          <a href="/" class="text-2xl font-bold">STADIUM</a>
        </div>
        
        <!-- Десктопное меню -->
        <div v-if="showNavLinks" class="nav-links hidden md:flex space-x-8">
          <a href="/" class="nav-link">Главная</a>
          <a href="/#artists" class="nav-link">Артисты</a>
          <a href="/search" class="nav-link">Поиск</a>
          <a href="/#events" class="nav-link">Мероприятия</a>
        </div>
        
        <div class="flex items-center">
          <a href="/profile" class="nav-link mr-4 md:mr-0">Личный кабинет</a>
          <!-- Мобильное меню -->
          <mobile-menu v-if="showNavLinks"></mobile-menu>
        </div>
      </div>
      
      <div v-if="isHomePage" class="text-center mt-8 md:mt-12 mb-8 md:mb-12">
        <h1 class="text-3xl md:text-5xl font-bold">МУЛЬТИФОРМАТНАЯ ЛЕТНЯЯ ПЛОЩАДКА</h1>
      </div>
      
      <div v-if="isArtistPage && artist" class="artist-info mt-8">
        <div class="flex flex-col items-end space-y-4">
          <div class="bg-red-600 skewed-box-right p-3 md:p-4">
            <div class="skewed-content-right">
              <p class="text-white">Москва,</p>
              <p class="text-white">Лето</p>
              <p class="text-white">в Лужниках</p>
            </div>
          </div>
          
          <div class="bg-yellow-300 skewed-box-left p-3 md:p-4">
            <div class="skewed-content-left">
              <p class="text-white">{{ eventDate }}</p>
              <p class="text-white">20:00</p>
            </div>
          </div>
          
          <div class="bg-blue-500 skewed-box-right p-3 md:p-4">
            <div class="skewed-content-right">
              <a :href="bookingUrl" class="text-white">Купить билет</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import MobileMenu from './MobileMenu.vue';

export default {
  components: {
    MobileMenu
  },
  props: {
    isHomePage: {
      type: Boolean,
      default: false
    },
    isArtistPage: {
      type: Boolean,
      default: false
    },
    artist: {
      type: Object,
      default: null
    },
    eventDate: {
      type: String,
      default: ''
    },
    eventId: {
      type: Number,
      default: null
    },
    showNavLinks: {
      type: Boolean,
      default: true
    }
  },
  computed: {
    headerClass() {
      if (this.isHomePage) {
        return 'gradient-header py-6 md:py-8';
      } else if (this.isArtistPage && this.artist) {
        return `artist-header bg-cover bg-center py-8 md:py-12`;
      } else {
        return 'bg-white py-4 md:py-6';
      }
    },
    bookingUrl() {
      return this.eventId ? `/events/${this.eventId}/booking` : '#';
    }
  }
}
</script>

<style scoped>
.gradient-header {
  background: linear-gradient(to right, #0084FF, #E8FA5F);
  min-height: 30vh;
}

@media (min-width: 768px) {
  .gradient-header {
    min-height: 35vh;
  }
}

.artist-header {
  min-height: 400px;
  position: relative;
}

@media (min-width: 768px) {
  .artist-header {
    min-height: 650px;
  }
}

.nav-link {
  color: #000;
  font-weight: 500;
  transition: color 0.3s;
}

.nav-link:hover {
  color: #0084FF;
}

.skewed-box-right, .skewed-box-left {
  position: relative;
  transform: skew(-15deg);
  margin-bottom: 10px;
  min-width: 150px;
}

.skewed-content-right, .skewed-content-left {
  transform: skew(15deg);
  padding: 5px 15px;
}
</style>