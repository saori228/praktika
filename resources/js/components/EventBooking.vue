<template>
  <div class="booking-page">
    <div class="event-info mb-8 md:mb-12 bg-white p-6 rounded-lg shadow">
      <div class="flex flex-col md:flex-row">
        <div class="event-image w-full md:w-1/3 mb-4 md:mb-0">
          <img :src="event.display_image || event.image_path || '/images/events/event-default.jpg'" :alt="event.name" class="rounded-lg w-full h-48 md:h-64 object-cover">
        </div>
        
        <div class="event-details w-full md:w-2/3 md:pl-8">
          <h1 class="text-xl md:text-2xl font-bold mb-3 md:mb-4">{{ event.name }}</h1>
          <p class="text-gray-700 text-sm md:text-base mb-2">{{ formatDate(event.event_date) }}</p>
          <p class="text-gray-700 text-sm md:text-base">{{ event.description }}</p>
        </div>
      </div>
    </div>
    
    <!-- Остальной код компонента остается без изменений -->
    <div class="venue-layout mb-6 md:mb-8">
      <div class="bg-white p-4 md:p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold mb-4">Выбор мест</h2>
        
        <div class="venue-container relative w-full overflow-x-auto">
          <!-- Концертный зал -->
          <div v-if="event.event_type && event.event_type.name === 'concert'" class="venue-block">
            <!-- Сцена -->
            <div class="stage-block">
              Сцена
            </div>
            
            <!-- Танцпол -->
            <div 
              v-if="getDanceFloorZone"
              @click="selectZone(getDanceFloorZone.id)"
              :class="{'selected': selectedZone === getDanceFloorZone.id}"
              class="dancefloor-block"
            >
              Танцпол
            </div>
            
            <!-- Премиум зона -->
            <div 
              v-if="getPremiumZone"
              @click="selectZone(getPremiumZone.id)"
              :class="{'selected': selectedZone === getPremiumZone.id}"
              class="premium-block"
            >
              Премиум
            </div>
            
            <!-- VIP зона -->
            <div 
              v-if="getVipZone"
              @click="selectZone(getVipZone.id)"
              :class="{'selected': selectedZone === getVipZone.id}"
              class="vip-block"
            >
              Активности + VIP зона
            </div>
            
            <!-- Фудкорт -->
            <div class="foodcourt-block">
              Фудкорт
            </div>
          </div>
          
          <!-- Кинозал -->
          <div v-else-if="event.event_type && event.event_type.name === 'movie'" class="venue-block">
            <!-- Экран -->
            <div class="screen-block">
              Экран
            </div>
            
            <!-- Обычные места -->
            <div class="movie-seats">
              <div v-for="row in 10" :key="`row-${row}`" class="seat-row">
                <div class="row-number">{{ row }}</div>
                <div v-for="seat in 15" :key="`seat-${row}-${seat}`" 
                     class="seat" 
                     :class="{
                       'selected': selectedSeats.includes(`${row}-${seat}`),
                       'unavailable': !isSeatAvailable(row, seat)
                     }"
                     @click="toggleSeat(`${row}-${seat}`)">
                  {{ seat }}
                </div>
              </div>
            </div>
            
            <!-- VIP места -->
            <div class="movie-vip-seats">
              <div v-for="row in 2" :key="`vip-row-${row}`">
                <div class="vip-seats-left">
                  <div v-for="seat in 5" :key="`vip-left-${row}-${seat}`" 
                       class="vip-seat" 
                       :class="{
                         'selected': selectedSeats.includes(`vip-left-${row}-${seat}`),
                         'unavailable': !isVipSeatAvailable('left', row, seat)
                       }"
                       @click="toggleSeat(`vip-left-${row}-${seat}`)">
                    {{ seat }}
                  </div>
                </div>
                <div class="vip-seats-right">
                  <div v-for="seat in 5" :key="`vip-right-${row}-${seat}`" 
                       class="vip-seat" 
                       :class="{
                         'selected': selectedSeats.includes(`vip-right-${row}-${seat}`),
                         'unavailable': !isVipSeatAvailable('right', row, seat)
                       }"
                       @click="toggleSeat(`vip-right-${row}-${seat}`)">
                    {{ seat }}
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Театр -->
          <div v-else-if="event.event_type && event.event_type.name === 'theater'" class="venue-block">
            <!-- Сцена -->
            <div class="theater-stage">
              Сцена
            </div>
            
            <!-- Обычные места -->
            <div class="theater-seats">
              <div v-for="row in 10" :key="`theater-row-${row}`" class="seat-row">
                <div class="row-number">{{ row }}</div>
                <div v-for="seat in 15" :key="`theater-seat-${row}-${seat}`" 
                     class="seat" 
                     :class="{
                       'selected': selectedSeats.includes(`theater-${row}-${seat}`),
                       'unavailable': !isTheaterSeatAvailable(row, seat)
                     }"
                     @click="toggleSeat(`theater-${row}-${seat}`)">
                  {{ seat }}
                </div>
              </div>
            </div>
            
            <!-- Переключатель этажей -->
            <div class="floor-switch">
              <button 
                @click="currentFloor = 1" 
                :class="{'active': currentFloor === 1}"
                class="floor-button"
              >
                1 этаж
              </button>
              <button 
                @click="currentFloor = 2" 
                :class="{'active': currentFloor === 2}"
                class="floor-button"
              >
                2 этаж
              </button>
            </div>
            
            <!-- Места на втором этаже -->
            <div v-if="currentFloor === 2" class="balcony-seats">
              <div class="balcony-row">
                <div v-for="seat in 25" :key="`balcony-seat-${seat}`" 
                     class="balcony-seat" 
                     :class="{
                       'selected': selectedSeats.includes(`balcony-${seat}`),
                       'unavailable': !isBalconySeatAvailable(seat)
                     }"
                     @click="toggleSeat(`balcony-${seat}`)">
                  {{ seat }}
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="venue-info mt-4 flex flex-col md:flex-row md:justify-between text-sm md:text-base">
          <div v-if="event.event_type && event.event_type.name === 'concert'">
            <div v-if="getVipZone" class="mb-2 md:mb-0">VIP - {{ getVipZone.price }} ₽</div>
            <div v-if="getDanceFloorZone" class="mb-2 md:mb-0">Танцпол - {{ getDanceFloorZone.price }} ₽</div>
            <div v-if="getPremiumZone" class="mb-2 md:mb-0">Премиум - {{ getPremiumZone.price }} ₽</div>
          </div>
          <div v-else-if="event.event_type && event.event_type.name === 'movie'">
            <div class="mb-2 md:mb-0">Обычные места - 250 ₽</div>
            <div class="mb-2 md:mb-0">VIP места - 450 ₽</div>
          </div>
          <div v-else-if="event.event_type && event.event_type.name === 'theater'">
            <div class="mb-2 md:mb-0">Обычные места - 250 ₽</div>
            <div class="mb-2 md:mb-0">Места на 2 этаже - 500 ₽</div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="booking-actions flex flex-col md:flex-row md:justify-end md:items-center bg-white p-4 rounded-lg shadow">
      <div class="price mb-4 md:mb-0 md:mr-4 text-lg md:text-xl font-bold text-center md:text-right">
        {{ totalPrice }} рублей
      </div>
      
      <div class="quantity mb-4 md:mb-0 md:mr-4" v-if="event.event_type && event.event_type.name === 'concert' && selectedZone">
        <label class="block text-sm font-medium text-gray-700 mb-1">Количество билетов:</label>
        <div class="flex items-center">
          <button @click="decrementQuantity" class="px-2 py-1 border rounded-l">-</button>
          <input type="number" v-model.number="quantity" min="1" max="10" class="w-12 text-center border-t border-b">
          <button @click="incrementQuantity" class="px-2 py-1 border rounded-r">+</button>
        </div>
      </div>
      
      <button 
        @click="bookTickets" 
        :class="{'opacity-50 cursor-not-allowed': !canBook}"
        :disabled="!canBook"
        class="book-button bg-white border-2 border-black rounded-full px-6 md:px-8 py-2 md:py-3 font-bold hover:bg-gray-100 transition-colors w-full md:w-auto text-center"
      >
        Забронировать
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    event: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      selectedZone: null,
      selectedSeats: [],
      bookingInProgress: false,
      quantity: 1,
      currentFloor: 1,
      unavailableSeats: []
    }
  },
  computed: {
    getDanceFloorZone() {
      if (!this.event.venue_zones) return null;
      return this.event.venue_zones.find(zone => zone.name.toLowerCase() === 'танцпол');
    },
    getPremiumZone() {
      if (!this.event.venue_zones) return null;
      return this.event.venue_zones.find(zone => zone.name.toLowerCase() === 'премиум');
    },
    getVipZone() {
      if (!this.event.venue_zones) return null;
      return this.event.venue_zones.find(zone => zone.name.toLowerCase().includes('vip'));
    },
    canBook() {
      if (!this.event.event_type) return false;
      
      if (this.event.event_type.name === 'concert') {
        return this.selectedZone && this.quantity > 0;
      } else {
        return this.selectedSeats.length > 0;
      }
    },
    totalPrice() {
      let price = 0;
      
      if (!this.event.event_type) return price;
      
      if (this.event.event_type.name === 'concert') {
        if (this.selectedZone) {
          const zone = this.event.venue_zones.find(z => z.id === this.selectedZone);
          if (zone) {
            price = zone.price * this.quantity;
          }
        }
      } else if (this.event.event_type.name === 'movie') {
        this.selectedSeats.forEach(seatId => {
          if (seatId.startsWith('vip')) {
            price += 450;
          } else {
            price += 250;
          }
        });
      } else if (this.event.event_type.name === 'theater') {
        this.selectedSeats.forEach(seatId => {
          if (seatId.startsWith('balcony')) {
            price += 500;
          } else {
            price += 250;
          }
        });
      }
      
      return price;
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
    },
    selectZone(zoneId) {
      if (this.event.event_type && this.event.event_type.name === 'concert') {
        this.selectedZone = zoneId;
        this.selectedSeats = [];
      }
    },
    toggleSeat(seatId) {
      if (this.isSeatUnavailable(seatId)) return;
      
      const index = this.selectedSeats.indexOf(seatId);
      if (index === -1) {
        this.selectedSeats.push(seatId);
      } else {
        this.selectedSeats.splice(index, 1);
      }
    },
    isSeatUnavailable(seatId) {
      return this.unavailableSeats.includes(seatId);
    },
    isSeatAvailable(row, seat) {
      return !this.isSeatUnavailable(`${row}-${seat}`);
    },
    isVipSeatAvailable(side, row, seat) {
      return !this.isSeatUnavailable(`vip-${side}-${row}-${seat}`);
    },
    isTheaterSeatAvailable(row, seat) {
      return !this.isSeatUnavailable(`theater-${row}-${seat}`);
    },
    isBalconySeatAvailable(seat) {
      return !this.isSeatUnavailable(`balcony-${seat}`);
    },
    incrementQuantity() {
      if (this.quantity < 10) {
        this.quantity++;
      }
    },
    decrementQuantity() {
      if (this.quantity > 1) {
        this.quantity--;
      }
    },
    async loadUnavailableSeats() {
      try {
        const response = await fetch(`/api/events/${this.event.id}/unavailable-seats`);
        if (response.ok) {
          const data = await response.json();
          this.unavailableSeats = data.seats || [];
        }
      } catch (error) {
        console.error('Ошибка загрузки занятых мест:', error);
      }
    },
    async bookTickets() {
      if (this.bookingInProgress || !this.canBook) return;
      
      this.bookingInProgress = true;
      
      try {
        let seatsToBook = [];
        
        if (this.event.event_type.name === 'concert') {
          if (!this.selectedZone) {
            alert('Пожалуйста, выберите зону');
            this.bookingInProgress = false;
            return;
          }
          
          const zone = this.event.venue_zones.find(z => z.id === this.selectedZone);
          if (zone) {
            for (let i = 0; i < this.quantity; i++) {
              seatsToBook.push({
                zone_id: zone.id,
                seat_id: `concert-${zone.id}-${Date.now()}-${i}`,
                price: zone.price
              });
            }
          }
        } else {
          if (this.selectedSeats.length === 0) {
            alert('Пожалуйста, выберите места');
            this.bookingInProgress = false;
            return;
          }
          
          this.selectedSeats.forEach(seatId => {
            let price = 0;
            let zoneId = null;
            
            if (this.event.event_type.name === 'movie') {
              if (seatId.startsWith('vip')) {
                price = 450;
                zoneId = this.getVipZone?.id;
              } else {
                price = 250;
                zoneId = this.event.venue_zones.find(z => z.name.toLowerCase() === 'обычные места')?.id;
              }
            } else if (this.event.event_type.name === 'theater') {
              if (seatId.startsWith('balcony')) {
                price = 500;
                zoneId = this.event.venue_zones.find(z => z.name.toLowerCase() === 'второй этаж')?.id;
              } else {
                price = 250;
                zoneId = this.event.venue_zones.find(z => z.name.toLowerCase() === 'обычные места')?.id;
              }
            }
            
            if (zoneId) {
              seatsToBook.push({
                zone_id: zoneId,
                seat_id: seatId,
                price: price
              });
            }
          });
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const response = await fetch(`/events/${this.event.id}/booking`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({
            seats: seatsToBook,
            total_price: this.totalPrice
          })
        });
        
        const result = await response.json();
        
        if (response.ok) {
          alert('Билеты успешно забронированы!');
          window.location.href = '/profile';
        } else {
          alert(result.message || 'Произошла ошибка при бронировании');
        }
      } catch (error) {
        console.error('Ошибка бронирования:', error);
        alert('Произошла ошибка при бронировании');
      } finally {
        this.bookingInProgress = false;
      }
    }
  },
  mounted() {
    if (this.event && this.event.id) {
      this.loadUnavailableSeats();
    } else {
      console.error('Ошибка: данные о мероприятии отсутствуют');
    }
  }
}
</script>

<style scoped>
.venue-block {
  position: relative;
  width: 1150px;
  height: 530px;
  background-color: #f5f5f5;
  border-radius: 8px;
  margin: 0 auto;
}

.stage-block, .screen-block, .theater-stage {
  position: absolute;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  width: 200px;
  height: 75px;
  background-color: #e0e0e0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
}

.dancefloor-block {
  position: absolute;
  top: 105px;
  left: 50%;
  transform: translateX(-50%);
  width: 200px;
  height: 300px;
  background-color: #e0e0e0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.premium-block {
  position: absolute;
  top: 105px;
  right: 250px;
  width: 180px;
  height: 300px;
  background-color: #e0e0e0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.vip-block {
  position: absolute;
  top: 105px;
  left: 100px;
  width: 320px;
  height: 300px;
  background-color: #e0e0e0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.foodcourt-block {
  position: absolute;
  top: 20px;
  left: 100px;
  width: 320px;
  height: 75px;
  background-color: #e0e0e0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
}

.dancefloor-block:hover, .premium-block:hover, .vip-block:hover,
.dancefloor-block.selected, .premium-block.selected, .vip-block.selected {
  background-color: #b3e0ff;
}

.movie-seats {
  position: absolute;
  top: 120px;
  left: 50%;
  transform: translateX(-50%);
  width: 600px;
}

.seat-row {
  display: flex;
  justify-content: center;
  margin-bottom: 10px;
}

.row-number {
  width: 20px;
  text-align: center;
  margin-right: 10px;
}

.seat {
  width: 30px;
  height: 30px;
  margin: 0 5px;
  background-color: #e0e0e0;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background-color 0.3s;
}

.seat:hover, .seat.selected {
  background-color: #b3e0ff;
}

.seat.unavailable {
  background-color: #ff9999;
  cursor: not-allowed;
}

.movie-vip-seats {
  position: absolute;
  top: 120px;
  width: 100%;
}

.vip-seats-left, .vip-seats-right {
  position: absolute;
  display: flex;
  flex-direction: column;
}

.vip-seats-left {
  left: 50px;
}

.vip-seats-right {
  right: 50px;
}

.vip-seat {
  width: 30px;
  height: 30px;
  margin: 5px;
  background-color: #ffd700;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background-color 0.3s;
}

.vip-seat:hover, .vip-seat.selected {
  background-color: #ffcc00;
}

.vip-seat.unavailable {
  background-color: #ff9999;
  cursor: not-allowed;
}

.theater-seats {
  position: absolute;
  top: 120px;
  left: 50%;
  transform: translateX(-50%);
  width: 600px;
}

.floor-switch {
  position: absolute;
  top: 20px;
  right: 20px;
  display: flex;
}

.floor-button {
  padding: 5px 10px;
  margin: 0 5px;
  background-color: #e0e0e0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.floor-button.active {
  background-color: #b3e0ff;
}

.balcony-seats {
  position: absolute;
  top: 120px;
  left: 50%;
  transform: translateX(-50%);
  width: 600px;
}

.balcony-row {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
}

.balcony-seat {
  width: 30px;
  height: 30px;
  margin: 5px;
  background-color: #a0d8ef;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background-color 0.3s;
}

.balcony-seat:hover, .balcony-seat.selected {
  background-color: #7fc9e6;
}

.balcony-seat.unavailable {
  background-color: #ff9999;
  cursor: not-allowed;
}
</style>
