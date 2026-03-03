import moment from "moment";
import { useTranslation } from '@/composables/useTranslation';

const { i18n } = useTranslation();

const lwxApp = import.meta.env.VITE_LWX;

// ============================== START LWX ==============================
let _Value = {
  _LWX: lwxApp,
};

let _LWX = lwxApp,
  _asciiBitAmt = 8,
  _defaultBaseNBitLen = 7,
  StringFromCharCode = String.fromCharCode,
  _mathPow = Math.pow,
  charCodeAt = (src, idx) => {
    return src.charCodeAt(idx);
  },
  _getSvrKey = () => {
    let tmp = _Value[_LWX];
    return _nBitDec(tmp); //better save encrypted key in array not string
  },
  getLength = (s) => {
    return s.length;
  },
  _genKey = (keyType) => {
    let _As = 65,
      _Ze = 91,
      _as = 97,
      _ze = 123,
      _0s = 48,
      _9e = 58,
      _QuestionMark_s = 63, //?
      _Colon_e = 59, //:
      _Number_Sign_s = 35, //#
      _Ampersand_e = 39, //Terminate before 39 (& actually 38)
      _Left_Parenthis_s = 40, //(
      _FullStop_e = 47, //Terminate before 47, FullStop actually 46
      _LeftSquareBracket_e = 92, //Terminate before 92, [ actually 91
      _RightSquareBracket_s = 93, //]
      _Low_Line_e = 96, //Terminate before 96, _ actually 95
      _Tilde_e = 127, //Terminate before 127, ~ actually 126
      _LatinAwGrave_s = 192,
      _LatinSmall_ae_e = 231, //Terminate before 231, ae actually 230
      _key = "",
      suffix = "",
      arrRange = [_As, _Ze, _as, _ze, _0s, _9e], //[[_As,_Ze],[_as,_ze],[_0s,_9e]],
      i = 0,
      j,
      k,
      l;
    if (keyType == 0) {
      // standard base 64
      suffix = "+/=";
    } else if (keyType == 1) {
      // non standard uri safe base 64
      suffix = "-_."; // standard uri safe using "+-$"
    } else if (keyType == 2) {
      // non standard base 64
      arrRange = [_as, _ze, _QuestionMark_s, _Ze, _0s, _Colon_e];
    } else if (keyType == 9) {
      // key was from server and session specific after successfull login
      arrRange = [];
      _key = _getSvrKey();
    } else {
      //own base 2 to base 128
      _key = "!";
      arrRange = [
        _Number_Sign_s,
        _Ampersand_e,
        _Left_Parenthis_s,
        _FullStop_e,
        _0s,
        _LeftSquareBracket_e,
        _RightSquareBracket_s,
        _Low_Line_e,
        _as,
        _Tilde_e,
        _LatinAwGrave_s,
        _LatinSmall_ae_e,
      ];
    }

    for (l = getLength(arrRange); i < l; i += 2) {
      for (j = arrRange[i], k = arrRange[i + 1]; j < k; j++) {
        _key += StringFromCharCode(j);
      }
    }
    return _key + suffix;
  },
  Includes = (array, value) => {
    return array.includes(value);
  },
  notIncludes = (array, value) => {
    return !array.includes(value);
  },
  _nBitEnc = (source, baseNBitLen, key) => {
    //return _bNE(baseNBitLen || 6, source, key);
    baseNBitLen = baseNBitLen || _defaultBaseNBitLen;
    key = key || _genKey();
    let binData = 0,
      bitLen = 0,
      baseNBit = _mathPow(2, baseNBitLen) - 1,
      encResult = source.replace(/./g, function (v) {
        let encResultTmp = "";
        binData = (binData << _asciiBitAmt) + charCodeAt(v, 0); //v.charCodeAt(0);
        bitLen += _asciiBitAmt;
        while (bitLen >= baseNBitLen) {
          bitLen -= baseNBitLen;
          encResultTmp += key[(binData >>> bitLen) & baseNBit];
          //binData = binData & (_mathPow(2,bitLen)-1);
        }
        return encResultTmp;
      });
    return bitLen > 0
      ? encResult + key[(binData << (baseNBitLen - bitLen)) & baseNBit]
      : encResult;
  },
  _nBitDec = (source, baseNBitLen, key) => {
    //return _bND(baseNBitLen || 6, source, key);
    baseNBitLen = baseNBitLen || _defaultBaseNBitLen;
    let binData = 0,
      bitLen = 0;
    key = key || _genKey();
    return source.replace(/./g, function (v) {
      // debugger
      binData = (binData << baseNBitLen) + key.indexOf(v);
      bitLen += baseNBitLen;
      return bitLen < _asciiBitAmt
        ? ""
        : StringFromCharCode((binData >>> (bitLen -= _asciiBitAmt)) & 0xff);
    });
  };

/**
 * base N bit per byte decrypt (base 64 bit decrypt)
 * @param {string} source encrypted source string
 * @param {number|string} edType decrypt type: -1, 0, 1, 2, 9 or key string
 * @param {number} nBitLen 6 for base 64, 7 for base 128, 5 for base 32, 4 for hexa decimal if passed key is "0123456789ABCDEF"
 * @example
 *  let myDecryptedString = Global.d(myEncryptedBase128String, 9) //using session dependend base64 key
 *  let myDecryptedString = Global.d(myEncryptedOwnBase64String) //using own base64 encrtption
 *  let myDecryptedString = Global.d(myEncryptedDefaultBase64String, 0) //using default base64 encrtption
 *  let myDecryptedString = Global.d(myEncryptedBase128String, -1, 7) //using default base128 encrtption
 */
const decrypt = (source, edType, nBitLen) => {
  if (!edType) {
    //default base 128 decrypt
    return _nBitDec(source);
  } else {
    //base 64 uri safe decrypt
    // debugger
    return _nBitDec(
      source,
      nBitLen || 6,
      Number.isInteger(edType) ? _genKey(edType) : edType,
    );
  }
};

/**
 * base N bit per byte encrypt (base 64 bit encrypt)
 * @param {string} source encrypted source string
 * @param {number|string} edType decrypt type: -1, 0, 1, 2, 9 or key string
 * @param {number} nBitLen 6 for base 64, 7 for base 128, 5 for base 32, 4 for hexa decimal if passed key is "0123456789ABCDEF"
 * @example
 *  let myEncryptedString = Global.enc(mySourceString, 9) //using session dependend base64 key
 *  let myEncryptedString = Global.enc(mySourceString) //using own base64 encrtption
 *  let myEncryptedString = Global.enc(mySourceString, 0) //using default base64 encrtption
 *  let myEncryptedString = Global.enc(mySourceString, -1, 7) //using default base128 encrtption
 */
const encrypt = (source, edType, nBitLen) => {
  // debugger;
  if (!edType) {
    //default base 128 encrypt
    return _nBitEnc(source);
  } else {
    //base 64 uri safe encrypt
    return _nBitEnc(
      source,
      nBitLen || 6,
      Number.isInteger(edType) ? _genKey(edType) : edType,
    );
  }
};

/**
 * generate new UUID
 * @param {boolean} formated whether to format result
 * @returns {string} UUID in hexadecimal form
 */
const getUUID = (formated) => {
  let format = formated
    ? "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx"
    : "xxxxxxxxyxxx4xxxyxxxyxxxxxxxxxxx";
  let d = new Date().getTime();
  if (window.performance && typeof window.performance.now === "function") {
    d += performance.now(); //use high-precision timer if available
  }
  return format.replace(/[xy]/g, function (c) {
    let r = (Math.random() * 17 + (d = Math.floor((d * 9) / 7))) % 16 | 0;
    return (c == "x" ? r : (r & 0x3) | 0x8).toString(16);
  });
};

/**
 * generate random string
 * @param {number} resultLength
 * @param {number} keyType
 * @param {number} additionalVarLen
 * @returns {string} random string with length between resultLength and resultLength+additionalVarLen
 */
const randomString = (resultLength, keyType, additionalVarLen) => {
  //let i=0, random = Math.random, round = Math.floor, result = '', key = _genKey(keyType || 1), keyLength=key.length;
  let i = 0,
    random = Math.random,
    round = Math.floor,
    result = "",
    key = _genKey(keyType || 1),
    keyLength = getLength(key);
  for (
    resultLength += additionalVarLen ? round(random() * additionalVarLen) : 0;
    i < resultLength;
    result += key[round(random() * keyLength)], i += 1
  );
  return result;
};

/**
 * trim string and add ...
 * @param {string} text source string
 * @param {number} maxLength maximum length, default 10
 * @returns {string} trimmed string with addition ...
 */
const cutText = (text, maxLength = 10) => {
  if (!text) return "";

  if (getLength(text) > maxLength) {
    //if (text.length > maxLength) {
    return text.substring(0, maxLength).concat("...");
  } else {
    return text;
  }
};
// ============================== END LWX ==============================

// ============================== START LSP ==============================
const { t } = i18n.global;
const setCookie = (name, value, days) => {
  let expires = "";
  if (days) {
    let date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    expires = `; expires=${date.toUTCString()}`;
  }
  document.cookie = `${name}=${value || ""}${expires}; path=/`;
};

const getCookie = (name) => {
  let nameEQ = name + "=";
  let ca = document.cookie.split("; ");
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) === " ") c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
  }
  return null;
};

const deleteCookie = (name) => {
  if (document.cookie.includes(name)) {
    document.cookie =
      name + "=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;";
  }
};

const isExpiredToken = (tokenExp) => {
  const isExpired = new Date(tokenExp) < Date.now();
  if (!isExpired) {
    return false;
  }
  return true;
};


const filteredData = (searchText = null, data, searchItem) => {
  if (
    searchText !== null &&
    searchText !== undefined &&
    data !== null &&
    data !== undefined &&
    searchItem !== null &&
    searchItem !== undefined
  ) {
    return data.filter((item) => {
      return searchText
        .toLowerCase()
        .split(" ")
        .every((value) => item[searchItem].toLowerCase().includes(value));
    });
  } else {
    return data;
  }
};

const sortData = (data, item, type) => {
  if (type === "desc")
    return data.sort((a, b) => {
      if (typeof a[item] !== "number" || typeof b[item] !== "number") {
        return a[item].toString().localeCompare(b[item].toString());
      }
      return b[item] - a[item];
    });
  else if (type === "asc")
    return data.sort((a, b) => {
      if (typeof a[item] !== "number" || typeof b[item] !== "number") {
        return a[item].toString().localeCompare(b[item].toString());
      }
      return a[item] - b[item];
    });
  else return 0;
};

const checkDateTime = (loc = "Asia/Jakarta") => {
  let f = new Intl.DateTimeFormat("en", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    weekday: "short",
    hour: "2-digit",
    hour12: false,
    minute: "2-digit",
    second: "2-digit",
    timeZone: loc,
  });
  let temp = f.formatToParts(new Date("2022-11-26T09:30:00"));
  let parts = temp.reduce((acc, part) => {
    if (part.type != "literal") {
      acc[part.type] = part.value;
    }
    return acc;
  }, {});

  return parts;

  // return parts.weekday != 'Sat' && parts.weekday != 'Sun' && parts.hour >= 8 && parts.hour < 17;
};

const timeDuration = (start, end) => {
  let oDiff = new Object();

  let startDate = new Date(start);
  let endDate = new Date(end);

  //  Calculate Differences
  let nTotalDiff = endDate.getTime() - startDate.getTime();

  oDiff.days = Math.floor(nTotalDiff / 1000 / 60 / 60 / 24);
  nTotalDiff -= oDiff.days * 1000 * 60 * 60 * 24;

  oDiff.hours = Math.floor(nTotalDiff / 1000 / 60 / 60);
  nTotalDiff -= oDiff.hours * 1000 * 60 * 60;

  oDiff.minutes = Math.floor(nTotalDiff / 1000 / 60);
  nTotalDiff -= oDiff.minutes * 1000 * 60;

  oDiff.seconds = Math.floor(nTotalDiff / 1000);

  //  Format Hours
  let hourtext = oDiff.days > 0 ? String(oDiff.days) : "00";
  if (hourtext.length === 1) hourtext = "0" + hourtext;

  //  Format Minutes
  let mintext = oDiff.minutes > 0 ? String(oDiff.minutes) : "00";
  if (mintext.length === 1) mintext = "0" + mintext;

  //  Format Seconds
  let sectext = oDiff.seconds > 0 ? String(oDiff.seconds) : "00";
  if (sectext.length === 1) sectext = "0" + sectext;

  //  Set Duration
  let sDuration = hourtext + ":" + mintext + ":" + sectext;
  oDiff.duration = sDuration;

  // Use oDiff to display days, hours, minutes, and seconds
  const { days, hours, minutes, seconds } = oDiff;

  return `${days > 0 ? days + " " + t("Days").toLowerCase() : ""}
            ${hours > 0 ? hours + " " + t("Hours").toLowerCase() : ""}
            ${minutes > 0 ? minutes + " " + t("Minutes").toLowerCase() : ""}
            ${seconds > 0 ? seconds + " " + t("Seconds").toLowerCase() : ""}`;
};

const changeLocale = (locale, type) => {
  if (typeof locale === "string" && locale.length > 0) {
    localStorage.setItem("locale", locale);
  } else {
    throw new Error("Invalid locale");
  }
  if (type === "dropdown") window.location.reload();
};

const dateFormatter = (params, type = "default") => {
  if (!params) {
    return "-";
  }
  let defaultOptions = null;
  const userTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;

  // Retrieve the locale from localStorage and map it accordingly
  const storedLocale = localStorage.getItem("locale");
  const lang = storedLocale === "en" ? "en-US" : "id-ID"; // Default mapping

  switch (type) {
    case "default":
      defaultOptions = {
        weekday: "short",
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        second: undefined,
        timeZone: userTimeZone,
        timeZoneName: "short",
      };
      break;

    case "fullDate":
      defaultOptions = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
        timeZone: userTimeZone,
      };
      break;

    case "fullDateShort":
      defaultOptions = {
        weekday: "short", // e.g., "Rab"
        year: "numeric", // e.g., "2024"
        month: "short", // e.g., "Sep"
        day: "numeric", // e.g., "11"
        timeZone: userTimeZone,
      };
      break;

    case "dateShort":
      defaultOptions = {
        // weekday: "short", // e.g., "Rab"
        year: "numeric", // e.g., "2024"
        month: "short", // e.g., "Sep"
        day: "numeric", // e.g., "11"
      };
      break;

    case "dateShort2":
      defaultOptions = {
        // weekday: "short", // e.g., "Rab"
        year: "numeric", // e.g., "2024"
        month: "long", // e.g., "Sep"
        day: "numeric", // e.g., "11"
      };
      break;

    case "fullTimeShort":
      defaultOptions = {
        hour: "2-digit", // e.g., "11"
        minute: "2-digit", // e.g., "05"
        second: "2-digit", // e.g., "03"
        timeZone: userTimeZone,
        timeZoneName: lang === "id-ID" ? "short" : undefined, // e.g., "WIB" for Indonesian
        hour12: lang === "en-US", // 12-hour format with AM/PM for English
      };
      break;

    case "shortDate":
      defaultOptions = {
        year: "numeric",
        month: "short",
        day: "numeric",
        timeZone: userTimeZone,
      };
      break;

    case "iso":
      return new Date(params).toISOString();

    case "compact":
      defaultOptions = {
        year: "2-digit",
        month: "numeric",
        day: "numeric",
        timeZone: userTimeZone,
      };
      break;

    case "dayMonth":
      defaultOptions = {
        month: "short",
        day: "numeric",
        timeZone: userTimeZone,
      };
      break;

    case "weekdayDate":
      defaultOptions = {
        weekday: "long",
        month: "short",
        day: "numeric",
        timeZone: userTimeZone,
      };
      break;

    case "monthYear":
      defaultOptions = {
        month: "long",
        year: "numeric",
        timeZone: userTimeZone,
      };
      break;

    case "timeWithSeconds":
      defaultOptions = {
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        timeZone: userTimeZone,
      };
      break;

    case "05:03":
      defaultOptions = {
        hour: "2-digit",
        minute: "2-digit",
        timeZone: userTimeZone,
      };
      break;

    case "datetime":
      defaultOptions = {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        timeZone: userTimeZone,
      };
      break;

    case "year":
      defaultOptions = {
        year: "numeric",
        timeZone: userTimeZone,
      };
      break;

    case "month":
      defaultOptions = {
        month: "long",
        year: "numeric",
        timeZone: userTimeZone,
      };
      break;

    case "date":
      defaultOptions = {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        timeZone: userTimeZone,
      };
      break;

    case "time":
      defaultOptions = {
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        timeZone: userTimeZone,
      };
      break;

    case "hour":
      defaultOptions = {
        hour: "2-digit",
        timeZone: userTimeZone,
      };
      break;

    case "minutes":
      defaultOptions = {
        minute: "2-digit",
        timeZone: userTimeZone,
      };
      break;

    case "seconds":
      defaultOptions = {
        second: "2-digit",
        timeZone: userTimeZone,
      };
      break;

    case "custom":
      defaultOptions = {
        ...params, // Assuming params is an object containing custom options
        timeZone: userTimeZone,
      };
      break;

    default:
      break;
  }

  return new Date(params).toLocaleString(lang, defaultOptions);
};

const formatNumber = (value) => {
  if (typeof value === "number") {
    return (
      new Intl.NumberFormat("id-ID", {
        minimumFractionDigits: 0,
      }).format(value) || 0
    );
  } else {
    return value || 0;
  }
};

const extractDate = (dateString) => {
  if (
    typeof dateString !== "string" ||
    !/^\d{4}-\d{2}-\d{2}$/.test(dateString)
  ) {
    throw new Error("Invalid dateString format");
  }
  // Extract date (YYYY-MM-DD) from the datetime string
  return dateString.split("T")[0];
};

const handleTime = (dateString) => {
  // Extract date (YYYY-MM-DD) from the datetime string
  let dateTime = moment(new Date(dateString)).format("YYYY-MM-DDTHH:mm:ss");
  return dateTime;
};

const getExpiredDuration = (expiredDate) => {
  if (expiredDate === null) {
    return "-";
  }

  const currentDate = new Date();
  const expiredDateTime = new Date(expiredDate);

  // Set currentDate time to 00:00:00.000
  currentDate.setHours(0, 0, 0, 0);

  // Set expiredDateTime time to 23:59:59.999
  expiredDateTime.setHours(23, 59, 59, 999);

  // Calculate the difference in milliseconds
  const timeDifference = expiredDateTime - currentDate;

  // Define time unit constants
  const millisecondsInSecond = 1000;
  const millisecondsInMinute = 60 * millisecondsInSecond;
  const millisecondsInHour = 60 * millisecondsInMinute;
  const millisecondsInDay = 24 * millisecondsInHour;
  const millisecondsInMonth = 30 * millisecondsInDay; // Assuming 30 days in a month for simplicity
  const millisecondsInYear = 365.25 * millisecondsInDay;

  if (timeDifference < 0) {
    const absTimeDifference = Math.abs(timeDifference);

    const pastYears = Math.floor(absTimeDifference / millisecondsInYear);
    const pastMonths = Math.floor(
      (absTimeDifference % millisecondsInYear) / millisecondsInMonth,
    );
    const pastDays = Math.floor(
      (absTimeDifference % millisecondsInMonth) / millisecondsInDay,
    );

    // Build the past result string
    let pastResult = "";
    if (pastYears > 0) {
      pastResult += `${pastYears} ${pastYears === 1 ? "year" : "years"} `;
    }
    if (pastMonths > 0) {
      pastResult += `${pastMonths} ${pastMonths === 1 ? "month" : "months"} `;
    }
    if (pastDays > 0) {
      pastResult += `${pastDays} ${pastDays === 1 ? "day" : "days"} `;
    }

    return pastResult.trim() === "" ? "Yesterday" : `${pastResult.trim()} ago`;
  }

  const futureYears = Math.floor(timeDifference / millisecondsInYear);
  const futureMonths = Math.floor(
    (timeDifference % millisecondsInYear) / millisecondsInMonth,
  );
  const futureDays = Math.floor(
    (timeDifference % millisecondsInMonth) / millisecondsInDay,
  );

  // Build the future result string
  let futureResult = "";
  if (futureYears > 0) {
    futureResult += `${futureYears} ${futureYears === 1 ? "year" : "years"} `;
  }
  if (futureMonths > 0) {
    futureResult += `${futureMonths} ${
      futureMonths === 1 ? "month" : "months"
    } `;
  }
  if (futureDays > 0) {
    futureResult += `${futureDays} ${futureDays === 1 ? "day" : "days"} `;
  }

  return futureResult.trim() === "" ? "Today" : `in ${futureResult.trim()}`;
};

const downloadFileFormUrl = (fileUrl, fileName) => {
  const link = document.createElement("a");
  link.href = fileUrl;
  link.download = fileName; // The file will be downloaded with this name
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link); // Cleanup
};

const downloadFileFromApi = (data, fileName) => {
  /// Check if response data is valid
  if (data && data.size > 0) {
    // Create a link element
    const link = document.createElement("a");
    const urlBlob = window.URL.createObjectURL(new Blob([data]));

    // Set link attributes
    link.href = urlBlob;
    link.download = fileName || "file.pdf"; // Default filename if not provided

    // Append link to the body
    document.body.appendChild(link);

    // Trigger the download
    link.click();

    // Clean up
    link.remove();
    window.URL.revokeObjectURL(urlBlob);
  } else {
    console.error("Received empty response or invalid blob:", response);
  }
};

const base64toFile = (fileName, base64Data, mimeType) => {
  // Convert base64 to binary data
  const byteCharacters = atob(base64Data);
  const byteNumbers = new Array(byteCharacters.length);
  for (let i = 0; i < byteCharacters.length; i++) {
    byteNumbers[i] = byteCharacters.charCodeAt(i);
  }
  const byteArray = new Uint8Array(byteNumbers);

  // Create a File object
  const file = new File([byteArray], fileName, { type: mimeType });

  // Store the file name to display
  // fileName.value = file.name;

  // You cannot set the value of the file input directly, but you can manually handle the file.
  // For example, you can store it in a variable and process it further.
  return file;
};

const showToast = (message, type = 'success', location = 'top-right', timeout = 3000) => {
    // Tunggu sedikit jika toast belum siap
    if (!window.$toast) {
        setTimeout(() => {
            window.$toast.show(message, type, location, timeout);
        }, 100); // fallback
    } else {
        window.$toast.show(message, type, location, timeout);
    }
}

// ============================== END LSP ==============================

export {
    getLength,
    notIncludes,
    decrypt,
    encrypt,
    getUUID,
    randomString,
    cutText,
    setCookie,
    getCookie,
    deleteCookie,
    isExpiredToken,
    filteredData,
    sortData,
    checkDateTime,
    timeDuration,
    changeLocale,
    dateFormatter,
    formatNumber,
    extractDate,
    handleTime,
    getExpiredDuration,
    downloadFileFormUrl,
    downloadFileFromApi,
    base64toFile,
    showToast,
    Includes,
};
