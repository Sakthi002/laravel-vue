import moment from 'moment';
export function formatDate(value) {

    return !value ? '' : moment(String(value)).format('YYYY-MM-DD');
}
