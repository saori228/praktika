<template>
  <footer :class="footerClass" class="mt-auto py-4">
    <div class="container mx-auto px-4">
      <div class="flex flex-col md:flex-row justify-between items-center">
        <div class="logo mb-3 md:mb-0">
          <a href="/" class="text-xl md:text-2xl font-bold">STADIUM</a>
        </div>
        
        <div class="contact-info text-center md:text-right text-sm md:text-base">
          <p>Связаться с нами можно: +7(000)000-00-00; stadium@bk.ru</p>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  props: {
    bgColor: {
      type: String,
      default: 'bg-blue-200'
    }
  },
  computed: {
    footerClass() {
      return this.bgColor;
    }
  }
}
</script>