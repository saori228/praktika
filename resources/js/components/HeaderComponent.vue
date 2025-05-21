<template>
  <header :class="headerClass" :style="headerStyle">
    <div class="container mx-auto px-4 py-4">
      <div class="flex justify-between items-center">
        <div class="logo">
          <a href="/" class="text-2xl font-bold">STADIUM</a>
        </div>
        
        <!-- Десктопное меню -->
        <div v-if="showNavLinks" class="nav-links hidden md:flex space-x-8">
          <a href="/" :class="navLinkClass">Главная</a>
          <a href="/#artists" :class="navLinkClass">Артисты</a>
          <a href="/search" :class="navLinkClass">Поиск</a>
          <a href="/#events" :class="navLinkClass">Мероприятия</a>
        </div>
        
        <div class="flex items-center">
          <a href="/profile" :class="navLinkClass + ' mr-4 md:mr-0'">Личный кабинет</a>
          <!-- Мобильное меню -->
          <mobile-menu v-if="showNavLinks"></mobile-menu>
        </div>
      </div>
      
      <div v-if="isHomePage" class="text-center mt-8 md:mt-12 mb-8 md:mb-12">
        <h1 class="text-3xl md:text-5xl font-bold">МУЛЬТИФОРМАТНАЯ ЛЕТНЯЯ ПЛОЩАДКА</h1>
      </div>
      
      <!-- Информация для страницы артиста или мероприятия -->
      <div v-if="(isArtistPage || isEventPage) && (artist || event)" class="artist-info mt-8">
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
              <p class="text-black">{{ eventDate }}</p>
              <p class="text-black">20:00</p>
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
    isEventPage: {
      type: Boolean,
      default: false
    },
    artist: {
      type: Object,
      default: null
    },
    event: {
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
      } else if ((this.isArtistPage || this.isEventPage) && (this.artist || this.event)) {
        return `artist-header bg-cover bg-center py-8 md:py-12`;
      } else {
        return 'bg-white py-4 md:py-6';
      }
    },
    headerStyle() {
      if (this.isArtistPage && this.artist && this.artist.image_path) {
        return { backgroundImage: `url(${this.artist.image_path})` };
      } else if (this.isEventPage && this.event && this.event.image_path) {
        return { backgroundImage: `url(${this.event.image_path})` };
      }
      return {};
    },
    navLinkClass() {
      return (this.isArtistPage || this.isEventPage) ? 'nav-link text-white' : 'nav-link text-black';
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

.artist-header::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 0;
}

.artist-header .container {
  position: relative;
  z-index: 1;
}

@media (min-width: 768px) {
  .artist-header {
    min-height: 650px;
  }
}

.nav-link {
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
  z-index: 2;
}

.skewed-content-right, .skewed-content-left {
  transform: skew(15deg);
  padding: 5px 15px;
}
</style>