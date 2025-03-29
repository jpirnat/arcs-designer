const { createApp } = Vue;

const app = createApp({
    data() {
        return {
            loading: true,
            loaded: false,

            iterations: [],
            current: {},
            comparing: null,
            affinities: [],
            speedModifiers: [],
            zoneModifiers: [],
            cardTypes: [],
            maxLengths: {},
        };
    },
    async created() {
        const url = new URL(window.location);

        const response = await fetch('/data' + url.pathname, {
            credentials: 'same-origin',
        })
        .then(response => response.json());

        this.loading = false;
        this.loaded = true;

        if (!response.data) {
            return;
        }

        const data = response.data;

        this.iterations = data.iterations;
        this.current = data.current;
        this.comparing = data.comparing;
        this.affinities = data.affinities;
        this.speedModifiers = data.speedModifiers;
        this.zoneModifiers = data.zoneModifiers;
        this.cardTypes = data.cardTypes;
        this.maxLengths = data.maxLengths;
    },
    methods: {
        compare(iteration) {
            if (iteration.iterationId === this.current.iterationId) {
                this.comparing = null;
                return;
            }

            this.comparing = iteration;
        },

        showLifeBurden(iteration) {
            return iteration.zoneModifier === 'leader';
        },
        showAttackDefense(iteration) {
            return iteration.cardType === 'creature';
        },

        async save() {
            const url = new URL(window.location);

            this.loading = true;
            const response = await fetch(url.pathname, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    iterationId: this.current.iterationId,
                    name: this.current.name,
                    affinityId: this.current.affinityId,
                    cost: this.current.cost,
                    enflowable: this.current.enflowable,
                    speedModifier: this.current.speedModifier,
                    zoneModifier: this.current.zoneModifier,
                    startingLife: this.showLifeBurden ? this.current.startingLife : '',
                    burden: this.showLifeBurden ? this.current.burden : '',
                    cardType: this.current.cardType,
                    rulesText: this.current.rulesText,
                    attack: this.showAttackDefense ? this.current.attack : '',
                    defense: this.showAttackDefense ? this.current.defense : '',
                }),
            })
            .then(response => response.json());
            this.loading = false;

            if (response.error) {
                const error = response.error;

                Swal.fire({
                    icon: 'error',
                    text: error.message,
                });
            }
        },

        copyComparingIntoCurrent() {
            this.current = this.comparing;
            this.comparing = null;
        },

        setComparingAsCurrent() {
        },
    },
});

app.mount('#app');
