module Components
  module BuyButton
    class BuyButtonComponent < Middleman::Extension
      helpers do
        def buy_button(opts)
          product = opts[:product]

          product_sizes = product.data.sizes.map { |x| x[:name] }.join('|') unless product.data.sizes.empty?

          classes = "snipcart-add-item inline-block w-28 py-2 px-2
                     bg-green-default border border-solid border-green-default
                     text-white text-base font-medium transition-all #{additional_classes(opts)}
                     hover:bg-green-darker hover:border-green-darker
                     motion-reduce:transition-none motion-reduce:transform-none"

          buy_button_html(product, product_image(product), product_sizes, classes)
        end

        def additional_classes(opts)
          opts.dig(:html, :class) ? " #{opts[:html][:class]}" : ''
        end

        def placeholder
          base_url + image_path('/assets/images/store/placeholder.png')
        end

        def product_image(product)
          product.data.images ? product.data.images.first : placeholder
        end
      end
    end
  end
end

::Middleman::Extensions.register(:buy_button_component, Components::BuyButton::BuyButtonComponent)
