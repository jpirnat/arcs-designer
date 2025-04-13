const { createApp } = Vue;

const app = createApp({
    data() {
        return {
            isFirstLoadComplete: false,
            isLoading: true,

            set: [],
            cards: [],
            maxLengths: {},

            original: {},
        };
    },
    computed: {
        changedOriginalName() {
            return this.set.name !== this.original.name;
        },
        changedOriginal() {
            return this.changedOriginalName;
        },
    },
    async created() {
        const url = new URL(window.location);

        const response = await fetch('/data' + url.pathname, {
            credentials: 'same-origin',
        })
        .then(response => response.json());

        this.isFirstLoadComplete = true;
        this.isLoading = false;

        if (!response.data) {
            return;
        }

        const data = response.data;

        this.set = data.set;
        this.cards = data.cards;
        this.maxLengths = data.maxLengths;

        this.original = {
            name: data.set.name,
        };
    },
    methods: {
        async save() {
            const url = new URL(window.location);

            this.isLoading = true;
            const response = await fetch(url.pathname, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    setId: this.set.id,
                    name: this.set.name,
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
                window.location.assign('/sets');
            }
        },
    },
});

app.mount('#app');
