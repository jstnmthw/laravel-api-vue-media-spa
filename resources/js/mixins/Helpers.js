/**
 * Global Mixin Helpers
 */
export default {
  mounted() {},
  methods: {
    // Format to user friendly number
    uf_num(int) {
      console.log('User friendly called')
      return int.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }
  }
}
