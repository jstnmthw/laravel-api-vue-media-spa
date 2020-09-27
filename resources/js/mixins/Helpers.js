/**
 * Global Mixin Helpers
 */
export default {
  mounted() {},
  methods: {
    // Format number with comma delimiter
    comma_delimiter(int) {
      console.log('User friendly called')
      return int.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }
  }
}
