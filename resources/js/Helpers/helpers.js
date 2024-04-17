export function getDateFromString(value) {
    // value = value.replaceAll(',', '')
    value = customReplaceAll(value, ',', '');
    let separatedDate = value.split(' ');
    //dla daty w formacie yyyy-mm-dd hh:mm:ss
    if (separatedDate[0].includes('-')) {
        let temp = []
        separatedDate[0].split('-').forEach((item, index) => {
            if (index === 1) {
                item = parseInt(item) - 1
            }
            temp.push(item)
        })
        if (separatedDate.length > 1) {
            separatedDate[1].split(':').forEach(item => {
                temp.push(item)
            })
        }
        return new Date(...temp)
        // dla daty w formacie dd.mm.yyyy hh:mm:ss
    } else if (separatedDate[0].includes('.')) {
        let temp = []
        separatedDate[0].split('.').reverse().forEach((item, index) => {
            if (index === 1) {
                item = parseInt(item) - 1
            }
            temp.push(item)
        })
        if (separatedDate.length > 1) {
            separatedDate[1].split(':').forEach(item => {
                temp.push(item)
            })
        }
        return new Date(...temp)
    }
};

export function getStringFromDate(value, withDots = true) {
    // let res = value.toLocaleString('pl-PL').replaceAll(' o ', ', ')
    let res = customReplaceAll(value.toLocaleString('pl-PL'), ' o ', ', ')
    if (!withDots) {
        let temp = res.split(', ')
        temp[0] = temp[0].split('.').reverse().join('-')
        res = temp.join(' ')

        // dodaje '0' na poczatku daty, jesli dzien jest z zakresu 1-9
        if (res.split(',')[0].split('-')[0].length === 1) {
            res = '0' + res
        }
    } else {
        if (res.split(',')[0].split('.')[0].length === 1) {
            res = '0' + res
        }
    }

    return res
}

export function customReplaceAll(sentence, str1, str2, ignore) {
    return sentence.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g, "\\$&"), (ignore ? "gi" : "g")), (typeof (str2) == "string") ? str2.replace(/\$/g, "$$$$") : str2);
}

export function addLeadingZero(val) {
    return val<10 ? ('0'+val) : val;
}

export function scrollToError() {
    setTimeout(() => {
        if (document.querySelector('.error')) {
            document.querySelector('.error').scrollIntoView({ behavior: "smooth", block: "center", inline: "center" });
        }
    }, 1);
}

export function prepareDateForRequest(date) {
    if (date.split(', ').length>1) {
        return date.split(', ')[0].split('.').reverse().join('-')+' '+date.split(', ')[1]
    } else {
        return date.split(', ')[0].split('.').reverse().join('-');
    }
}
export function prepareDateForSend(date, type, classesWeekDay) {
    const weekDay = date.getDay(); //dzien tygodnia wynikajacy z kalendarza
    const chosenWeekday = classesWeekDay%7; //dzien tygodnia wybrany z selecta
    const preparedDate = new Date(date);
    let res;
    if (type === 'from') {
        // jezeli wybrany dzien tygodnia jest pozniejszy lub taki sam niz dzien tygodnia wynikajacy z kalendarza, wówczas odejmujemy od początkowej daty
        if (chosenWeekday >= weekDay) {
            res = preparedDate.setDate(preparedDate.getDate() - weekDay);
        }
        // jezeli wybrany dzien tygodnia jest wczesniej niz dzien tygodnia wynikajacy z kalendarza, wowczas dodajemy do poczatkowej daty
        else {
            res = preparedDate.setDate(preparedDate.getDate() + ((7-weekDay)%7));
        }
    }

    if (type === 'to') {
        if ((chosenWeekday > weekDay) || chosenWeekday ===0) {
            res = preparedDate.setDate(preparedDate.getDate() - weekDay);
        } else {
            res = preparedDate.setDate(preparedDate.getDate() + ((7-weekDay)%7));
        }
    }
    return getStringFromDate(new Date(res)).split(', ')[0].split('.').reverse().join('-')
}

export function calculateTopDistance(timeStart, timeFrom) {
    const minutesHeight = parseInt(timeStart.split(':')[1]) / 60 * 96;
    const hoursHeight = ((parseInt(timeStart.split(':')[0]) - parseInt(timeFrom.split(':')[0])) * 96) + 48
    return (minutesHeight + hoursHeight);
}

export function singleTileHeight(timeStart, timeEnd) {
    const startParts = timeStart.split(':').map(Number);
    const endParts = timeEnd.split(':').map(Number);

    const startMinutes = startParts[0] * 60 + startParts[1];
    const endMinutes = endParts[0] * 60 + endParts[1];
    return (((endMinutes - startMinutes) / 60) * 96);
}

export function getUuid() {
    const randomNumber = Math.random();
    const hexString = (randomNumber).toString(16).slice(2, 17);
    return hexString
}
