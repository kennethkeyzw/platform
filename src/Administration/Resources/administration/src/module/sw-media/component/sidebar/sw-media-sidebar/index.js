import { Component } from 'src/core/shopware';
import template from './sw-media-sidebar.html.twig';
import './sw-media-sidebar.less';
import '../sw-media-quickinfo';
import '../sw-media-quickinfo-multiple';

Component.register('sw-media-sidebar', {
    template,

    props: {
        items: {
            required: false,
            type: [Array],
            validator(value) {
                const invalidElements = value.filter((element) => {
                    return element.type !== 'media';
                });
                return invalidElements.length === 0;
            }
        }
    },

    watch: {
        items(value) {
            if (value === undefined || value === null) {
                this.$refs.quickInfoButton.toggleContentPanel(false);
            }
        }
    },

    data() {
        return {
            autoplay: false
        };
    },

    computed: {
        hasItems() {
            return Array.isArray(this.items);
        },

        isSingleFile() {
            return this.hasItems && this.items.length === 1;
        },

        getKey() {
            if (!this.isSingleFile) {
                return '';
            }

            const item = this.items[0];
            let key = '';

            if (this.item) {
                key = item.id;
            }
            return key + this.autoplay;
        }
    },

    methods: {
        emitRequestRemoveSelection(originalDomEvent) {
            this.$emit('sw-media-sidebar-remove-batch', { originalDomEvent });
        },

        showQuickInfo() {
            this.$refs.quickInfoButton.toggleContentPanel(true);
        }
    }
});