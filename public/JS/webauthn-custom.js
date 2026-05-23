// Base64URL encoding/decoding functions
function bufferToBase64url(buffer) {
    const bytes = new Uint8Array(buffer);
    let str = '';
    for (let i = 0; i < bytes.byteLength; i++) {
        str += String.fromCharCode(bytes[i]);
    }
    return btoa(str).replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '');
}

function base64urlToBuffer(base64url) {
    const padding = '='.repeat((4 - base64url.length % 4) % 4);
    const base64 = (base64url + padding).replace(/\-/g, '+').replace(/_/g, '/');
    const rawData = atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray.buffer;
}

// Convert Base64URL strings to ArrayBuffers in the creation options
function preformatMakeCredReq(makeCredReq) {
    const options = Object.assign({}, makeCredReq);
    
    if (options.challenge) {
        options.challenge = base64urlToBuffer(options.challenge);
    }
    
    if (options.user && options.user.id) {
        options.user.id = base64urlToBuffer(options.user.id);
    }
    
    if (options.excludeCredentials) {
        options.excludeCredentials = options.excludeCredentials.map(cred => {
            const newCred = Object.assign({}, cred);
            newCred.id = base64urlToBuffer(newCred.id);
            return newCred;
        });
    }
    
    return options;
}

// Convert ArrayBuffers back to Base64URL strings for the creation response
function formatMakeCredRes(makeCredRes) {
    return {
        id: makeCredRes.id,
        rawId: bufferToBase64url(makeCredRes.rawId),
        type: makeCredRes.type,
        response: {
            attestationObject: bufferToBase64url(makeCredRes.response.attestationObject),
            clientDataJSON: bufferToBase64url(makeCredRes.response.clientDataJSON),
            transports: makeCredRes.response.getTransports ? makeCredRes.response.getTransports() : []
        }
    };
}

// Convert Base64URL strings to ArrayBuffers in the verification options
function preformatGetAssertReq(getAssertReq) {
    const options = Object.assign({}, getAssertReq);
    
    if (options.challenge) {
        options.challenge = base64urlToBuffer(options.challenge);
    }
    
    if (options.allowCredentials) {
        options.allowCredentials = options.allowCredentials.map(cred => {
            const newCred = Object.assign({}, cred);
            newCred.id = base64urlToBuffer(newCred.id);
            return newCred;
        });
    }
    
    return options;
}

// Convert ArrayBuffers back to Base64URL strings for the verification response
function formatGetAssertRes(getAssertRes) {
    return {
        id: getAssertRes.id,
        rawId: bufferToBase64url(getAssertRes.rawId),
        type: getAssertRes.type,
        response: {
            authenticatorData: bufferToBase64url(getAssertRes.response.authenticatorData),
            clientDataJSON: bufferToBase64url(getAssertRes.response.clientDataJSON),
            signature: bufferToBase64url(getAssertRes.response.signature),
            userHandle: getAssertRes.response.userHandle ? bufferToBase64url(getAssertRes.response.userHandle) : null
        }
    };
}

window.customWebAuthn = {
    create: async function(options) {
        const publicKey = preformatMakeCredReq(options.publicKey);
        const credential = await navigator.credentials.create({ publicKey });
        return formatMakeCredRes(credential);
    },
    get: async function(options) {
        const publicKey = preformatGetAssertReq(options.publicKey);
        const credential = await navigator.credentials.get({ publicKey });
        return formatGetAssertRes(credential);
    }
};
