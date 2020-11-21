// Format number with comma delimiter
export const comma_delimiter = (number = 0) => {
  return number.isNumber()
    ? number.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    : 0
}
