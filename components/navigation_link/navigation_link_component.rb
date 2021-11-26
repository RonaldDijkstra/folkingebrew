module Components
  module NavigationLink
    class NavigationLinkComponent < Middleman::Extension
      helpers do
        def navigation_link(opts)
          current_link_to opts[:text],
                          (opts[:link]).to_s,
                          class: 'flex items-center text-white hover:text-gray-300 px-3 text-lg font-medium whitespace-nowrap'
        end
      end
    end
  end
end

::Middleman::Extensions.register(:navigation_link_component, Components::NavigationLink::NavigationLinkComponent)
