const { createApp } = Vue;

const app = createApp({
    data() {
        return {
            isFirstLoadComplete: false,
            loading: true,

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
        this.loading = false;

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
        },
    },
});

app.mount('#app');
