module Components
  module ProductCard
    class ProductCardComponent < Middleman::Extension
      helpers do 
        def product_card(opts)
          product = opts[:product]
          image = product.images[0].url
    
          concat(
            link_to("/store/#{product.slug}", class: "bg-white p-6") do
              image_tag(image, alt: product.title, class: "mb-3", loading: "lazy") +
              content_tag(:div, class: "flex") do 
                content_tag(:h2, product.title, class: "flex-grow font-bold mr-3") +
                build_price(product)
              end 
            end
          )
        end

        def build_price(product)
          return old_price(product) + current_price(product) if product.old_price
          current_price(product)
        end

        def current_price(product) 
          price = number_to_currency(product.price, :unit => "").gsub!(/\./,",")
          content_tag(:div, "€ #{price}" , class: "font-bold whitespace-nowrap text-green-default")
        end 

        def old_price(product)
          price = number_to_currency(product.old_price, :unit => "").gsub!(/\./,",")
          content_tag(:div, "€ #{price}" , class: "font-bold relative line-through font-regular whitespace-nowrap text-red-500 mr-3")
        end 
      end
    end
  end
end

::Middleman::Extensions.register(:product_card_component, Components::ProductCard::ProductCardComponent)