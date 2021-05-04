export const urlSlug = (string = '') => {
  return string.replace('/[^/\\p{L} 0-9]/', '').toLowerCase()
}
