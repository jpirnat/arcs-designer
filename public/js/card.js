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

            original: {},
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

        this.original = {
            iterationId: data.current.iterationId,
            name: data.current.name,
            affinityId: data.current.affinityId,
            cost: data.current.cost,
            enflowable: data.current.enflowable,
            speedModifier: data.current.speedModifier,
            zoneModifier: data.current.zoneModifier,
            startingLife: data.current.startingLife,
            burden: data.current.burden,
            cardType: data.current.cardType,
            rulesText: data.current.rulesText,
            attack: data.current.attack,
            defense: data.current.defense,
        };
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
                return;
            }

            if (response.data) {
                window.location.assign('/cards');
            }
        },

        resetChanges() {
            this.current.name = this.original.name;
            this.current.affinityId = this.original.affinityId;
            this.current.cost = this.original.cost;
            this.current.enflowable = this.original.enflowable;
            this.current.speedModifier = this.original.speedModifier;
            this.current.zoneModifier = this.original.zoneModifier;
            this.current.startingLife = this.original.startingLife;
            this.current.burden = this.original.burden;
            this.current.cardType = this.original.cardType;
            this.current.rulesText = this.original.rulesText;
            this.current.attack = this.original.attack;
            this.current.defense = this.original.defense;
        },

        copyComparingIntoCurrent() {
            this.current.name = this.comparing.name;
            this.current.affinityId = this.comparing.affinityId;
            this.current.cost = this.comparing.cost;
            this.current.enflowable = this.comparing.enflowable;
            this.current.speedModifier = this.comparing.speedModifier;
            this.current.zoneModifier = this.comparing.zoneModifier;
            this.current.startingLife = this.comparing.startingLife;
            this.current.burden = this.comparing.burden;
            this.current.cardType = this.comparing.cardType;
            this.current.rulesText = this.comparing.rulesText;
            this.current.attack = this.comparing.attack;
            this.current.defense = this.comparing.defense;

            this.comparing = null;
        },

        setComparingAsCurrent() {
        },
    },
});

app.mount('#app');
