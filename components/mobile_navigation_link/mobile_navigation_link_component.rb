module Components
  module MobileNavigationLink
    class MobileNavigationLinkComponent < Middleman::Extension
      helpers do 
        def mobile_navigation_link(opts)
          current_link_to opts[:text], 
                          "#{opts[:link]}", 
                          class: "text-white hover:text-gray-300 block px-3 py-2 rounded-md text-lg font-medium"
        end
      end
    end
  end
end

::Middleman::Extensions.register(:mobile_navigation_link_component, Components::MobileNavigationLink::MobileNavigationLinkComponent)