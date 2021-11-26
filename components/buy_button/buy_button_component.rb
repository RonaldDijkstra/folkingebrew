module Components
  module BuyButton
    class BuyButtonComponent < Middleman::Extension
      helpers do
        def buy_button(opts)
          product = opts[:product]

          additional_classes = opts.dig(:html, :class) ? " #{opts[:html][:class]}" : ''

          product_image = product.images ? product.images.first.url : base_url + image_path('/assets/images/store/placeholder.png')

          unless product.sizes.empty?
            sizes = product.sizes.map { |x| x[:size] }.join('|')
            first_available_size = product.sizes.select(&:size_in_stock).first.size
          end

          classes = "snipcart-add-item inline-block text-white w-28 py-2 px-2 text-base bg-green-default border-green-default text-white hover:bg-green-darker hover:border-green-darker font-semibold transition-all border border-solid #{additional_classes}"

          if sizes
            content_tag(:button, 'Add to Cart',
                        class: classes,
                        "data-item-id": product.product_id,
                        "data-item-quantity": '1',
                        "data-item-price": product.price,
                        "data-item-name": product.title,
                        "data-item-max-quantity": product.max_quantity,
                        "data-item-custom1-name": 'Sizes',
                        "data-item-custom1-value": first_available_size,
                        "data-item-custom1-options": sizes,
                        "data-item-url": "#{base_url}/store/#{product.slug}",
                        "data-item-image": product_image,
                        "data-item-has-taxes-included": 'true')
          else
            content_tag(:button, 'Add to Cart',
                        class: classes,
                        "data-item-id": product.product_id,
                        "data-item-quantity": '1',
                        "data-item-price": product.price,
                        "data-item-name": product.title,
                        "data-item-max-quantity": product.max_quantity,
                        "data-item-url": "#{base_url}/store/#{product.slug}",
                        "data-item-image": product_image,
                        "data-item-has-taxes-included": 'true')
          end
        end
      end
    end
  end
end

::Middleman::Extensions.register(:buy_button_component, Components::BuyButton::BuyButtonComponent)
