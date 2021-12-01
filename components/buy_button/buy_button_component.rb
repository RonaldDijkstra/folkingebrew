module Components
  module BuyButton
    class BuyButtonComponent < Middleman::Extension
      helpers do
        def buy_button(opts)
          product = opts[:product]

          product_sizes = product.sizes.map { |x| x[:size] }.join('|') unless product.sizes.empty?

          classes = "snipcart-add-item inline-block text-white w-28 py-2 px-2 text-base bg-green-default
                     border-green-default text-white hover:bg-green-darker hover:border-green-darker
                     font-semibold transition-all border border-solid #{additional_classes(opts)}"

          if product_sizes
            buy_button_with_sizes(product, product_image(product), product_sizes, classes)
          else
            buy_button_without_sizes(product, product_image(product), classes)
          end
        end

        def additional_classes(opts)
          opts.dig(:html, :class) ? " #{opts[:html][:class]}" : ''
        end

        def placeholder
          base_url + image_path('/assets/images/store/placeholder.png')
        end

        def product_image(product)
          product.images ? product.images.first.url : placeholder
        end
      end
    end
  end
end

::Middleman::Extensions.register(:buy_button_component, Components::BuyButton::BuyButtonComponent)
