import { formatDistance, parseISO } from 'date-fns';


const relativeDate = (date) => formatDistance(parseISO(date), new Date(), { addSufix: true });

export {
    relativeDate,
};
