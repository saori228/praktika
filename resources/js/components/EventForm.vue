<template>
  <div class="container mx-auto px-4">
    <h1 class="text-2xl md:text-3xl font-bold mb-6 text-center">
      {{ isEditing ? 'Редактирование мероприятия' : 'Создание нового мероприятия' }}
    </h1>
    
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-4xl mx-auto">
      <form @submit.prevent="submitForm">
        <div class="mb-4">
          <label for="name" class="block text-gray-700 font-medium mb-2">Название мероприятия</label>
          <input 
            type="text" 
            id="name" 
            v-model="formData.name" 
            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          >
        </div>
        
        <div class="mb-4">
          <label for="description" class="block text-gray-700 font-medium mb-2">Описание</label>
          <textarea 
            id="description" 
            v-model="formData.description" 
            rows="4"
            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          ></textarea>
        </div>
        
        <div class="mb-4">
          <label for="event_date" class="block text-gray-700 font-medium mb-2">Дата мероприятия</label>
          <input 
            type="date" 
            id="event_date" 
            v-model="formData.event_date" 
            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          >
        </div>
        
        <div class="mb-4">
          <label for="event_type_id" class="block text-gray-700 font-medium mb-2">Тип мероприятия</label>
          <select 
            id="event_type_id" 
            v-model="formData.event_type_id" 
            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          >
            <option value="" disabled>Выберите тип мероприятия</option>
            <option v-for="type in eventTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
          </select>
        </div>
        
        <div class="mb-4">
          <label for="artist_id" class="block text-gray-700 font-medium mb-2">Артист</label>
          <select 
            id="artist_id" 
            v-model="formData.artist_id" 
            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option value="">Без артиста</option>
            <option v-for="artist in artists" :key="artist.id" :value="artist.id">{{ artist.name }}</option>
          </select>
        </div>
        
        <div class="mb-4">
          <label for="image_path" class="block text-gray-700 font-medium mb-2">Путь к изображению</label>
          <input 
            type="text" 
            id="image_path" 
            v-model="formData.image_path" 
            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="/images/events/event-name.jpg"
          >
        </div>
        
        <div class="flex justify-end space-x-4">
          <a 
            :href="cancelUrl" 
            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors"
          >
            Отмена
          </a>
          <button 
            type="submit" 
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            :disabled="isSubmitting"
          >
            {{ isSubmitting ? 'Сохранение...' : (isEditing ? 'Сохранить' : 'Создать') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    isEditing: {
      type: Boolean,
      default: false
    },
    event: {
      type: Object,
      default: () => ({})
    },
    eventTypes: {
      type: Array,
      required: true
    },
    artists: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      formData: {
        name: '',
        description: '',
        event_date: '',
        event_type_id: '',
        artist_id: '',
        image_path: ''
      },
      isSubmitting: false
    }
  },
  computed: {
    cancelUrl() {
      return '/profile/organizer';
    },
    submitUrl() {
      return this.isEditing ? `/events/${this.event.id}` : '/events';
    },
    submitMethod() {
      return this.isEditing ? 'PUT' : 'POST';
    }
  },
  methods: {
    async submitForm() {
      if (this.isSubmitting) return;
      
      this.isSubmitting = true;
      
      try {
        const formData = new FormData();
        
        // Добавляем все поля формы
        for (const key in this.formData) {
          if (this.formData[key] !== null && this.formData[key] !== undefined) {
            formData.append(key, this.formData[key]);
          }
        }
        
        // Для PUT запросов добавляем метод
        if (this.submitMethod === 'PUT') {
          formData.append('_method', 'PUT');
        }
        
        // Добавляем CSRF токен
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('_token', csrfToken);
        
        const response = await fetch(this.submitUrl, {
          method: 'POST',
          body: formData
        });
        
        if (response.ok) {
          window.location.href = this.cancelUrl;
        } else {
          const data = await response.json();
          alert(data.message || 'Произошла ошибка при сохранении мероприятия');
        }
      } catch (error) {
        console.error('Ошибка при отправке формы:', error);
        alert('Произошла ошибка при сохранении мероприятия');
      } finally {
        this.isSubmitting = false;
      }
    }
  },
  mounted() {
    // Если редактируем существующее мероприятие, заполняем форму данными
    if (this.isEditing && this.event) {
      this.formData.name = this.event.name || '';
      this.formData.description = this.event.description || '';
      this.formData.event_date = this.event.event_date ? this.event.event_date.split('T')[0] : '';
      this.formData.event_type_id = this.event.event_type_id || '';
      this.formData.artist_id = this.event.artist_id || '';
      this.formData.image_path = this.event.image_path || '';
    }
  }
}
</script>