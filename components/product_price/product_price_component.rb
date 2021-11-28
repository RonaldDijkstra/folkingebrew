module Components
  module ProductPrice
    class ProductPriceComponent < Middleman::Extension
      helpers do
        def product_price(product)
          return old_price(product) + current_price(product) if product.old_price

          current_price(product)
        end

        def current_price(product)
          price = number_to_currency(product.price, unit: '').gsub!(/\./, ',')
          content_tag(:div, "€ #{price}", class: 'font-bold whitespace-nowrap text-green-default')
        end

        def old_price(product)
          price = number_to_currency(product.old_price, unit: '').gsub!(/\./, ',')
          content_tag(:div, "€ #{price}",
                      class: 'font-bold relative line-through font-regular whitespace-nowrap text-red-500 mr-3')
        end
      end
    end
  end
end

::Middleman::Extensions.register(:product_price_component, Components::ProductPrice::ProductPriceComponent)
