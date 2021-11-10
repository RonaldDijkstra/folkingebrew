module Components
  module BuyButton
    class BuyButtonComponent < Middleman::Extension
      helpers do
        def buy_button(opts) 
          product = opts[:product]

          if product.images
            product_image = product.images.first.url
          else 
            product_image = base_url + image_path("/assets/images/store/placeholder.png")
          end 

          if !product.sizes.empty?
            sizes = product.sizes.map{|x| x[:size]}.join('|')
            first_available_size = product.sizes.select{ |s| s.size_in_stock }.first.size
          end

          classes = "snipcart-add-item buy-button px-4 py-2 h-auto font-semibold transition-all border-2 border-solid bg-green-default border-green-default text-white hover:bg-green-darker hover:border-green-darker"

          if sizes 
            content_tag(:button, "Add to Cart", 
                      class: classes, 
                      "data-item-id": product.product_id, 
                      "data-item-quantity": "1",
                      "data-item-price": product.price,
                      "data-item-name": product.title,
                      "data-item-max-quantity": product.max_quantity,
                      "data-item-custom1-name": "Sizes",
                      "data-item-custom1-value": first_available_size,
                      "data-item-custom1-options": sizes,
                      "data-item-url": base_url + "/store/" + product.slug,
                      "data-item-image": product_image,
                      "data-item-has-taxes-included": "true"
                      )
          else 
            content_tag(:button, "Add to Cart", 
                      class: classes, 
                      "data-item-id": product.product_id, 
                      "data-item-quantity": "1",
                      "data-item-price": product.price,
                      "data-item-name": product.title,
                      "data-item-max-quantity": product.max_quantity,
                      "data-item-url": base_url + "/store/" + product.slug,
                      "data-item-image": product_image,
                      "data-item-has-taxes-included": "true"
                      )
          end 
        end 
      end
    end
  end
end

::Middleman::Extensions.register(:buy_button_component, Components::BuyButton::BuyButtonComponent)