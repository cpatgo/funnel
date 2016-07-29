// b64.js

var adesk_b64_dec = {
    'A':  0, 'B':  1, 'C':  2, 'D':  3, 'E':  4, 'F':  5, 'G':  6, 'H':  7,
    'I':  8, 'J':  9, 'K': 10, 'L': 11, 'M': 12, 'N': 13, 'O': 14, 'P': 15,
    'Q': 16, 'R': 17, 'S': 18, 'T': 19, 'U': 20, 'V': 21, 'W': 22, 'X': 23,
    'Y': 24, 'Z': 25, 'a': 26, 'b': 27, 'c': 28, 'd': 29, 'e': 30, 'f': 31,
    'g': 32, 'h': 33, 'i': 34, 'j': 35, 'k': 36, 'l': 37, 'm': 38, 'n': 39,
    'o': 40, 'p': 41, 'q': 42, 'r': 43, 's': 44, 't': 45, 'u': 46, 'v': 47,
    'w': 48, 'x': 49, 'y': 50, 'z': 51, '0': 52, '1': 53, '2': 54, '3': 55,
    '4': 56, '5': 57, '6': 58, '7': 59, '8': 60, '9': 61, '-': 62, '!': 63,
    '=': 0
};

var adesk_b64_enc = {
     0: 'A',  1: 'B',  2: 'C',  3: 'D',  4: 'E',  5: 'F',  6: 'G',  7: 'H',
     8: 'I',  9: 'J', 10: 'K', 11: 'L', 12: 'M', 13: 'N', 14: 'O', 15: 'P',
    16: 'Q', 17: 'R', 18: 'S', 19: 'T', 20: 'U', 21: 'V', 22: 'W', 23: 'X',
    24: 'Y', 25: 'Z', 26: 'a', 27: 'b', 28: 'c', 29: 'd', 30: 'e', 31: 'f',
    32: 'g', 33: 'h', 34: 'i', 35: 'j', 36: 'k', 37: 'l', 38: 'm', 39: 'n',
    40: 'o', 41: 'p', 42: 'q', 43: 'r', 44: 's', 45: 't', 46: 'u', 47: 'v',
    48: 'w', 49: 'x', 50: 'y', 51: 'z', 52: '0', 53: '1', 54: '2', 55: '3',
    56: '4', 57: '5', 58: '6', 59: '7', 60: '8', 61: '9', 62: '-', 63: '!'
};

function adesk_b64_elshift(m, i, sh) {
    return (m.charCodeAt(i) << sh) & 63;
}

function adesk_b64_ershift(m, i, sh) {
    return (m.charCodeAt(i) >> sh) & 63;
}

// Base-64 encode a string, essentially by taking a 3-character block
// and turning it into a 4-character block using the base-64 alphabet.
// If less than 3 characters exist in the last block, the equal sign is
// used as padding (2 equal signs if only 1 character, 1 equal sign if 2
// characters).

function adesk_b64_encode(message) {
    var out = "";
    var buf0;
    var buf1;
    var buf2;
    var buf3;
    var i;

    for (i = 0; i < message.length; i += 3) {
        buf0 = adesk_b64_enc[adesk_b64_ershift(message, i+0, 2)];
        buf2 = "_";
        buf3 = "_";

        if ((i+1) < message.length)
            buf1 = adesk_b64_enc[adesk_b64_elshift(message, i+0, 4) | adesk_b64_ershift(message, i+1, 4)];
        else
            buf1 = adesk_b64_enc[adesk_b64_elshift(message, i+0, 4)];

        if ((i+2) < message.length) {
            buf2 = adesk_b64_enc[adesk_b64_elshift(message, i+1, 2) | adesk_b64_ershift(message, i+2, 6)];
            buf3 = adesk_b64_enc[adesk_b64_elshift(message, i+2, 0)];
        } else if ((i+1) < message.length)
            buf2 = adesk_b64_enc[adesk_b64_elshift(message, i+1, 2)];

        out += buf0 + buf1 + buf2 + buf3;
    }

    return out;
}

function adesk_b64_dlshift(c, sh) {
    return (adesk_b64_dec[c] << sh) & 255;
}

function adesk_b64_drshift(c, sh) {
    return (adesk_b64_dec[c] >> sh) & 255;
}

function adesk_b64_decode(message) {
    var out = "";
    var i;

    // All base-64 blocks are multiples of four characters.  Try it:
    // encode a one-letter string.  You'll get four characters
    // in return.  If that's not the case with this message, then it's
    // not really base-64 encoded (or not encoded correctly).

    if ((message.length % 4) != 0)
        return message;

    // Each block of four encoded characters can be decoded to, at most,
    // three unencoded ones.  (Which makes sense: 4 * 6bits = 24bits,
    // and 3 * 8bits = 24bits.)  The bits in base-64 are encoded
    // left-to-right, that is, starting with the high-order bit and
    // moving to the low-order bit.  Each number we consider has a bit
    // mask of 255 applied, so only (low-order) 8 bits are considered at
    // any given moment.

    // The equal sign is considered "padding" in an encoded string, but
    // they also represent the end marker.  A block of four bytes with
    // two equal signs on the end is a signal that only one character is
    // encoded; with one equal sign, two characters encoded.  No equal
    // sign is necessary if the initial string's length was a multiple
    // of 3.

    for (i = 0; i < message.length; i += 4) {
        out += String.fromCharCode(adesk_b64_dlshift(message.charAt(i+0), 2) | adesk_b64_drshift(message.charAt(i+1), 4)); if (message.charAt(i+2) == '_') break;
        out += String.fromCharCode(adesk_b64_dlshift(message.charAt(i+1), 4) | adesk_b64_drshift(message.charAt(i+2), 2)); if (message.charAt(i+3) == '_') break;
        out += String.fromCharCode(adesk_b64_dlshift(message.charAt(i+2), 6) | adesk_b64_drshift(message.charAt(i+3), 0));
    }
    
    return out;
}
