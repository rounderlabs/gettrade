const inputError = {
    beforeUpdate(el, binding) {
        if (binding.value) {
            $(el).addClass('is-invalid')
        }
    }
}

export default inputError
