import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler.js';

// Импортируем компоненты
import HeaderComponent from './components/HeaderComponent.vue';
import FooterComponent from './components/FooterComponent.vue';
import ArtistsList from './components/ArtistsList.vue';
import EventsList from './components/EventsList.vue';
import SearchComponent from './components/SearchComponent.vue';
import EventBooking from './components/EventBooking.vue';
import ProfileComponent from './components/ProfileComponent.vue';
import EventForm from './components/EventForm.vue';
import MobileMenu from './components/MobileMenu.vue';

// Создаем приложение Vue
const app = createApp({});

// Регистрируем компоненты
app.component('header-component', HeaderComponent);
app.component('footer-component', FooterComponent);
app.component('artists-list', ArtistsList);
app.component('events-list', EventsList);
app.component('search-component', SearchComponent);
app.component('event-booking', EventBooking);
app.component('profile-component', ProfileComponent);
app.component('event-form', EventForm);
app.component('mobile-menu', MobileMenu);

// Монтируем приложение
app.mount('#app');