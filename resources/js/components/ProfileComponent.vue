<template>
  <div class="profile-page bg-white">
    <div class="container mx-auto px-4 py-6 md:py-8">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
        <div class="personal-info bg-white p-6 rounded-lg shadow">
          <h2 class="text-xl md:text-2xl font-bold mb-4 md:mb-6">Личные данные</h2>
          
          <div class="user-data space-y-3 md:space-y-4">
            <div class="flex flex-col md:flex-row md:items-center">
              <div class="font-semibold mb-1 md:mb-0 md:w-32">Имя:</div>
              <div class="mb-1 md:mb-0">{{ user.name }}</div>
              <button @click="editField('name')" class="text-blue-600 hover:text-blue-800 self-start md:ml-4">
                Изменить
              </button>
            </div>
            
            <div class="flex flex-col md:flex-row md:items-center">
              <div class="font-semibold mb-1 md:mb-0 md:w-32">Фамилия:</div>
              <div class="mb-1 md:mb-0">{{ user.surname }}</div>
              <button @click="editField('surname')" class="text-blue-600 hover:text-blue-800 self-start md:ml-4">
                Изменить
              </button>
            </div>
            
            <div class="flex flex-col md:flex-row md:items-center">
              <div class="font-semibold mb-1 md:mb-0 md:w-32">Email:</div>
              <div class="mb-1 md:mb-0">{{ user.email }}</div>
              <button @click="editField('email')" class="text-blue-600 hover:text-blue-800 self-start md:ml-4">
                Изменить
              </button>
            </div>
            
            <div class="flex flex-col md:flex-row md:items-center">
              <div class="font-semibold mb-1 md:mb-0 md:w-32">Пароль:</div>
              <div class="mb-1 md:mb-0">**********</div>
              <button @click="editField('password')" class="text-blue-600 hover:text-blue-800 self-start md:ml-4">
                Изменить
              </button>
            </div>
          </div>
          
          <button @click="logout" class="mt-6 md:mt-8 text-red-600 hover:text-red-800">
            Выйти из аккаунта
          </button>
        </div>
        
        <div class="order-history bg-white p-6 rounded-lg shadow">
          <h2 class="text-xl md:text-2xl font-bold mb-4 md:mb-6">История заказов:</h2>
          
          <div v-if="bookings.length === 0" class="text-gray-500">
            У вас пока нет заказов
          </div>
          
          <div v-else class="space-y-3 md:space-y-4">
            <div v-for="booking in bookings" :key="booking.id" class="booking-item p-3 md:p-4 border rounded-lg">
              <div class="flex flex-col md:flex-row md:items-center">
                <div class="flex-1 mb-2 md:mb-0">
                  <div class="font-semibold">{{ booking.event.name }}</div>
                  <div class="text-sm text-gray-600">
                    {{ formatDate(booking.event.event_date) }}
                    <span v-if="isEventPassed(booking.event.event_date)" class="text-red-500 ml-2">
                      /событие прошло
                    </span>
                  </div>
                </div>
                <div class="md:text-right">
                  <div class="font-semibold">{{ booking.total_price }} ₽</div>
                  <div class="text-sm text-gray-600">{{ booking.booking_seats.length }} билет(ов)</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Секция для организаторов -->
      <div v-if="isOrganizer" class="organizer-section mt-8 md:mt-12 bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl md:text-2xl font-bold mb-4 md:mb-6 text-center">Для организаторов: создание мероприятия</h2>
        
        <div class="flex flex-col md:flex-row md:justify-center space-y-4 md:space-y-0 md:space-x-8">
          <a href="/profile/organizer" class="action-button bg-white border-2 border-black rounded-full px-6 md:px-8 py-2 md:py-3 font-bold hover:bg-gray-100 transition-colors text-center">
            Редактировать
          </a>
          
          <a href="/events/create" class="action-button bg-white border-2 border-black rounded-full px-6 md:px-8 py-2 md:py-3 font-bold hover:bg-gray-100 transition-colors text-center">
            Создать новое
          </a>
          
          <a href="/profile/organizer" class="action-button bg-white border-2 border-black rounded-full px-6 md:px-8 py-2 md:py-3 font-bold hover:bg-gray-100 transition-colors text-center">
            Удаление
          </a>
        </div>
      </div>
      
      <!-- Секция для администраторов -->
      <div v-if="isAdmin" class="admin-section mt-8 md:mt-12 bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl md:text-2xl font-bold mb-4 md:mb-6 text-center">Панель администратора</h2>
        
        <div class="flex flex-col md:flex-row md:justify-center space-y-4 md:space-y-0 md:space-x-8">
          <a href="/admin" class="action-button bg-white border-2 border-black rounded-full px-6 md:px-8 py-2 md:py-3 font-bold hover:bg-gray-100 transition-colors text-center">
            Панель управления
          </a>
          
          <a href="/admin/events" class="action-button bg-white border-2 border-black rounded-full px-6 md:px-8 py-2 md:py-3 font-bold hover:bg-gray-100 transition-colors text-center">
            Все мероприятия
          </a>
          
          <a href="/admin/users" class="action-button bg-white border-2 border-black rounded-full px-6 md:px-8 py-2 md:py-3 font-bold hover:bg-gray-100 transition-colors text-center">
            Пользователи
          </a>
        </div>
      </div>
      
      <!-- Модальное окно для редактирования -->
      <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg p-4 md:p-8 max-w-md w-full">
          <h3 class="text-lg md:text-xl font-bold mb-4">Изменить {{ getFieldLabel }}</h3>
          
          <form @submit.prevent="saveChanges">
            <div class="mb-4">
              <label class="block text-gray-700 mb-2">{{ getFieldLabel }}</label>
              <input 
                v-model="editValue" 
                :type="editingField === 'password' ? 'password' : 'text'" 
                class="w-full p-2 border rounded"
                :placeholder="getFieldPlaceholder"
              >
              <p v-if="fieldError" class="text-red-500 mt-1">{{ fieldError }}</p>
            </div>
            
            <div class="flex justify-end space-x-4">
              <button 
                type="button" 
                @click="cancelEdit" 
                class="px-4 py-2 border rounded hover:bg-gray-100"
              >
                Отмена
              </button>
              <button 
                type="submit" 
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
              >
                Сохранить
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    user: {
      type: Object,
      required: true
    },
    bookings: {
      type: Array,
      default: () => []
    },
    isOrganizer: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      showEditModal: false,
      editingField: null,
      editValue: '',
      fieldError: null
    }
  },
  computed: {
    isAdmin() {
      return this.user.email === 'sasha123no@gmail.com' || this.user.role === 'admin';
    },
    getFieldLabel() {
      const labels = {
        name: 'имя',
        surname: 'фамилию',
        email: 'email',
        password: 'пароль'
      };
      return labels[this.editingField] || '';
    },
    getFieldPlaceholder() {
      const placeholders = {
        name: 'Введите имя',
        surname: 'Введите фамилию',
        email: 'Введите email',
        password: 'Введите новый пароль'
      };
      return placeholders[this.editingField] || '';
    }
  },
  methods: {
    editField(field) {
      this.editingField = field;
      this.editValue = field === 'password' ? '' : this.user[field];
      this.fieldError = null;
      this.showEditModal = true;
    },
    cancelEdit() {
      this.showEditModal = false;
      this.editingField = null;
      this.editValue = '';
      this.fieldError = null;
    },
    async saveChanges() {
      // Валидация
      if (this.editingField === 'email' && !this.validateEmail(this.editValue)) {
        this.fieldError = 'Email должен быть в формате @gmail.com';
        return;
      }
      
      if (this.editingField === 'password' && !this.validatePassword(this.editValue)) {
        this.fieldError = 'Пароль должен содержать от 8 до 30 символов и хотя бы одну заглавную букву';
        return;
      }
      
      try {
        const endpoint = this.editingField === 'password' 
          ? '/profile/password' 
          : '/profile/update';
        
        const data = this.editingField === 'password'
          ? { password: this.editValue }
          : { [this.editingField]: this.editValue };
        
        const response = await fetch(endpoint, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (response.ok) {
          // Обновляем данные пользователя в UI
          if (this.editingField !== 'password') {
            this.user[this.editingField] = this.editValue;
          }
          
          this.showEditModal = false;
          this.editingField = null;
          this.editValue = '';
          
          // Показываем сообщение об успехе
          alert('Данные успешно обновлены');
        } else {
          this.fieldError = result.message || 'Произошла ошибка при обновлении данных';
        }
      } catch (error) {
        console.error('Ошибка обновления данных:', error);
        this.fieldError = 'Произошла ошибка при обновлении данных';
      }
    },
    validateEmail(email) {
      return email.endsWith('@gmail.com');
    },
    validatePassword(password) {
      return password.length >= 8 && password.length <= 30 && /[A-Z]/.test(password);
    },
    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleDateString('ru-RU');
    },
    isEventPassed(dateString) {
      const eventDate = new Date(dateString);
      const now = new Date();
      return eventDate < now;
    },
    logout() {
      // Отправляем запрос на выход
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '/logout';
      
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const csrfInput = document.createElement('input');
      csrfInput.type = 'hidden';
      csrfInput.name = '_token';
      csrfInput.value = csrfToken;
      
      form.appendChild(csrfInput);
      document.body.appendChild(form);
      form.submit();
    }
  }
}
</script>