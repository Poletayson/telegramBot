'use strict';

let constants = {
    // PUSHER_APP_KEY: "supportChat",
    // PUSHER_APP_CLUSTER: "mt1",
    PUSHER_HOSTNAME: "",
    PUSHER_PORT: 6001
    // key3: {
    //     subkey1: "subvalue1",
    //     subkey2: "subvalue2"
    // }
};

module.exports =
    Object.freeze(constants); // freeze prevents changes by users
