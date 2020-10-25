/**
 * Global Mixin Helpers
 */
export default {
  methods: {
    // Format number with comma delimiter
    comma_delimiter(int = false) {
      return int ? int.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') : 0
    }
  }
}
