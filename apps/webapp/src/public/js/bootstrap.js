/**
 * Formatea una fecha a formato largo en español
 * @param {string|Date} fecha
 * @returns {string}
 */
window.formatearFechaLarga = function(fecha) {
    if (!fecha) return '';

    return dayjs(fecha).format('dddd, D [de] MMMM [de] YYYY');
};
