// import Echo from "laravel-echo";
//
//
// const getToken = (email, password) => {
//     return 'Bearer ' + btoa(JSON.stringify({
//         email, password
//     }));
// };
//
// const getHeader = (user) => {
//     const token = getToken(user.email, user.unhashed_password);
//
//     return {
//         'WWW-Authenticate': `${token}`
//     };
// };
//
// export default {
//     getEchoInstance() {
//         return new Echo({
//             broadcaster: 'socket.io',
//             host: 'http://documents' + ':6001',
//         });
//     }
// };
