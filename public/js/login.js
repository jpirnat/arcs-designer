const { createApp } = Vue;

const app = createApp({
    data() {
        return {
            isLoading: false,

            emailAddress: '',
            password: '',
        };
    },
    methods: {
        async login() {
            const url = new URL(window.location);

            this.isLoading = true;
            const response = await fetch(url.pathname, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    emailAddress: this.emailAddress,
                    password: this.password,
                }),
            })
            .then(response => response.json());
            this.isLoading = false;

            if (response.error) {
                const error = response.error;

                Swal.fire({
                    icon: 'error',
                    text: error.message,
                });
                return;
            }

            if (response.data) {
                window.location.assign('/cards');
            }
        },
    },
});

app.mount('#app');
