<template>
  <component :is="field.fullSize ? 'full-width-field' : 'default-field'" :field="field" full-width-content>
    <template slot="field">
      <div :class="{'px-8 pt-6': field.fullSize}">
        <gallery slot="value" ref="gallery" v-if="hasSetInitialValue"
                 v-model="value" editable custom-properties :field="field" :multiple="field.multiple"
                 :has-error="hasError" :first-error="firstError"/>
      </div>
    </template>
  </component>
</template>

<script>
  import { FormField, HandlesValidationErrors } from 'laravel-nova'
  import Gallery from '../Gallery';
  import FullWidthField from '../FullWidthField';
  import objectToFormData from 'object-to-formdata';

  export default {
    mixins: [FormField, HandlesValidationErrors],
    components: {
      Gallery,
      FullWidthField,
    },
    props: ['resourceName', 'resourceId', 'field'],
    data() {
      return {
        hasSetInitialValue: false,
        component: null,
      }
    },

    methods: {
      findImageComponent(root) {
				root.$children.forEach(component => {
          if (
            _.get(component.field, 'component') == 'advanced-media-library-field'
            && _.get(component.field, 'attribute') == this.field.attribute.split('__')[1]
          ) {
            this.component = component
          } else {
            this.findImageComponent(component)
          }

				})
			},

      setInitialValue() {
        this.findImageComponent(this.$root);
        
        let group = this.$parent.group; 
        let groups = this.$parent.$parent.$parent.groups; 
        let old_key = this.$parent.group.key
        let new_key = this.$parent.group.key.slice(0,16);
        if (old_key != new_key) {
          this.$set(this.$parent.group, 'key', new_key)
          this.$set(this.$parent.$parent.$parent.groups, `${new_key}`, this.$parent.$parent.$parent.groups[old_key])
          this.$delete(this.$parent.$parent.$parent.groups, `${old_key}`)
          
          this.$set(this.$parent.$parent.$parent, 'order', this.$parent.$parent.$parent.order.map(item => {
            return item.length > 16 ? item.slice(0,16) : item
          }))
        }

        let value = this.field.value.filter(item => {
          return item.name.split('__')[0] == this.$parent.group.key
        }); 

        this.multiple = _.get(this.field, 'extraAttributes.multiple') ? true : false
          
        if (!this.multiple) {
          value = value.slice(0, 1);
        }

        this.value = value;
        this.hasSetInitialValue = true;
      },

      /**
       * Fill the given FormData object with the field's internal value.
       */
      fill(formData) {
        this.$set(
          this.component,
          'value',
          this.component.value.filter(item => item.file_name.split('__')[0] != this.$parent.group.key)
        )

        this.value.forEach((file, index) => {
          if (!file.id) {
            file = {...file, ...{
              name: this.$parent.group.key + '__' + file.name,
              file_name: this.$parent.group.key + '__' + file.file_name,
            }}
          }
          this.component.value.push(file)
        });
      },

      handleChange(value) {
        this.value = value
      },
    },
  };
</script>
<style>
form > div:nth-last-child(2) {
  display: none;
}
form > div:first-child {
    display: block;
}
.modal > div {
  padding-top: 100px;
}
</style>

