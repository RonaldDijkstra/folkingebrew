module Components
  module BeerCard
    class BeerCardComponent < Middleman::Extension
      helpers do 
        def beer_card(opts)
          beer = opts[:beer]
          image = beer.image.url(fm: "png")

          concat(
            link_to("/beers/#{beer.slug}", class: "bg-gray-800 relative") do
              image_tag(image, alt: beer.title, class: "block w-full", loading: "lazy") +
              content_tag(:div, class: "opacity-0 w-full h-full bg-black absolute top-0 left-0 flex flex-col justify-center text-center p-10 hover:opacity-100") do
                content_tag(:h2, beer.title, class: "text-white text-xl") +
                content_tag(:div, beer.style, class: "text-green-default")
              end  
            end
          )
        end
      end
    end
  end
end

::Middleman::Extensions.register(:beer_card_component, Components::BeerCard::BeerCardComponent)
