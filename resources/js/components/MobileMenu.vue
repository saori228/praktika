<template>
  <div class="mobile-menu">
    <!-- Кнопка открытия меню -->
    <button @click="isOpen = true" class="md:hidden text-black">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>
    
    <!-- Мобильное меню -->
    <div 
      v-if="isOpen" 
      class="fixed inset-0 bg-black bg-opacity-50 z-50 flex"
      @click="isOpen = false"
    >
      <div 
        class="bg-white w-64 h-full overflow-auto p-4"
        @click.stop
      >
        <div class="flex justify-between items-center mb-6">
          <a href="/" class="text-xl font-bold">STADIUM</a>
          <button @click="isOpen = false" class="text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <nav class="space-y-4">
          <a href="/" class="block py-2 hover:text-blue-600">Главная</a>
          <a href="/#artists" class="block py-2 hover:text-blue-600">Артисты</a>
          <a href="/search" class="block py-2 hover:text-blue-600">Поиск</a>
          <a href="/#events" class="block py-2 hover:text-blue-600">Мероприятия</a>
          <a href="/profile" class="block py-2 hover:text-blue-600">Личный кабинет</a>
        </nav>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isOpen: false
    }
  },
  watch: {
    isOpen(value) {
      // Блокируем прокрутку body когда меню открыто
      document.body.style.overflow = value ? 'hidden' : '';
    }
  },
  mounted() {
    // Закрываем меню при нажатии Escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.isOpen) {
        this.isOpen = false;
      }
    });
  }
}
</script>