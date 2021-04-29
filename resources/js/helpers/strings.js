// Slug - https://stackoverflow.com/a/5782563/7040631
export const slug = (string = '') => {
  string = string.replace(/^\s+|\s+$/g, '') // trim
  string = string.toLowerCase()
  string = string
    // .replace(/[^a-z0-9 -]/g, '') // remove invalid chars
    .replace(/\s+/g, '-') // collapse whitespace and replace by -
    .replace(/#+/g, '-') // collapse whitespace and replace by -
    .replace(/-+/g, '-') // collapse dashes

  return string
}

export const urlSlug = (string = '') => {
  return string.replace('/[^\\\\p{L} 0-9]/m', '').toLowerCase()
}
