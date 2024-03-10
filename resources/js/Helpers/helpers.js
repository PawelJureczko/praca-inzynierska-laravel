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
