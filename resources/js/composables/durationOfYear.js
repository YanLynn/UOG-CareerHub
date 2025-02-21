export function getDurationString(startDate, endDate) {
    const start = new Date(startDate);

    const end = endDate ? new Date(endDate) : new Date();

    let years = end.getFullYear() - start.getFullYear();
    let months = end.getMonth() - start.getMonth();
    let days = end.getDate() - start.getDate();


    if (days < 0) {
        months--;
    }

    if (months < 0) {
        years--;
        months += 12;
    }

    let durationParts = [];
    if (years > 0) durationParts.push(`${years} year${years > 1 ? 's' : ''}`);
    if (months > 0) durationParts.push(`${months} month${months > 1 ? 's' : ''}`);


    if (durationParts.length === 0) {
        durationParts.push('Less than a month');
    }

    return durationParts.join(', ');
}
