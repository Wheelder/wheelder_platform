<?php


trait Metadata{
   /*
    public function get_cities()
    {
        echo '<select name="country" id="" class="bg-white w-100 border border-seacondary border-opcity-25 p-2 fs-14 rounded-2">
        <option value="">Country/Area</option>
                                                                                <option value="Abbots Langley"
            >
            Abbots Langley</option>
                                            <option value="Aberaman"
            >
            Aberaman</option>
                                            <option value="Aberbargoed"
            >
            Aberbargoed</option>
                                            <option value="Abercarn"
            >
            Abercarn</option>
                                            <option value="Abercynon"
            >
            Abercynon</option>
                                            <option value="Aberdare"
            >
            Aberdare</option>
                                            <option value="Aberdeen"
            >
            Aberdeen</option>
                                            <option value="Abergavenny"
            >
            Abergavenny</option>
                                            <option value="Abergele"
            >
            Abergele</option>
                                            <option value="Abersychan"
            >
            Abersychan</option>
                                            <option value="Abertawe"
            >
            Abertawe</option>
                                            <option value="Abertillery"
            >
            Abertillery</option>
                                            <option value="Aberystwyth"
            >
            Aberystwyth</option>
                                            <option value="Abingdon"
            >
            Abingdon</option>
                                            <option value="Abram"
            >
            Abram</option>
                                            <option value="Accrington"
            >
            Accrington</option>
                                            <option value="Acle"
            >
            Acle</option>
                                            <option value="Acomb"
            >
            Acomb</option>
                                            <option value="Acton"
            >
            Acton</option>
                                            <option value="Adderbury"
            >
            Adderbury</option>
                                            <option value="Addingham"
            >
            Addingham</option>
                                            <option value="Addlestone"
            >
            Addlestone</option>
                                            <option value="Adel"
            >
            Adel</option>
                                            <option value="Adlington"
            >
            Adlington</option>
                                            <option value="Adwick le Street"
            >
            Adwick le Street</option>
                                            <option value="Ainsdale"
            >
            Ainsdale</option>
                                            <option value="Aintree"
            >
            Aintree</option>
                                            <option value="Airdrie"
            >
            Airdrie</option>
                                            <option value="Albrighton"
            >
            Albrighton</option>
                                            <option value="Alcester"
            >
            Alcester</option>
                                            <option value="Aldenham"
            >
            Aldenham</option>
                                            <option value="Alderholt"
            >
            Alderholt</option>
                                            <option value="Alderley Edge"
            >
            Alderley Edge</option>
                                            <option value="Aldershot"
            >
            Aldershot</option>
                                            <option value="Aldridge"
            >
            Aldridge</option>
                                            <option value="Alexandria"
            >
            Alexandria</option>
                                            <option value="Alford"
            >
            Alford</option>
                                            <option value="Alfreton"
            >
            Alfreton</option>
                                            <option value="Allestree"
            >
            Allestree</option>
                                            <option value="Allington"
            >
            Allington</option>
                                            <option value="Alloa"
            >
            Alloa</option>
                                            <option value="Almondbury"
            >
            Almondbury</option>
                                            <option value="Almondsbury"
            >
            Almondsbury</option>
                                            <option value="Alness"
            >
            Alness</option>
                                            <option value="Alnwick"
            >
            Alnwick</option>
                                            <option value="Alrewas"
            >
            Alrewas</option>
                                            <option value="Alsager"
            >
            Alsager</option>
                                            <option value="Altarnun"
            >
            Altarnun</option>
                                            <option value="Altofts"
            >
            Altofts</option>
                                            <option value="Alton"
            >
            Alton</option>
                                            <option value="Altrincham"
            >
            Altrincham</option>
                                            <option value="Alva"
            >
            Alva</option>
                                            <option value="Alvechurch"
            >
            Alvechurch</option>
                                            <option value="Alveston"
            >
            Alveston</option>
                                            <option value="Alyth"
            >
            Alyth</option>
                                            <option value="Amble"
            >
            Amble</option>
                                            <option value="Amblecote"
            >
            Amblecote</option>
                                            <option value="Ambleside"
            >
            Ambleside</option>
                                            <option value="Amersham"
            >
            Amersham</option>
                                            <option value="Amesbury"
            >
            Amesbury</option>
                                            <option value="Amlwch"
            >
            Amlwch</option>
                                            <option value="Ammanford"
            >
            Ammanford</option>
                                            <option value="Ampthill"
            >
            Ampthill</option>
                                            <option value="Andover"
            >
            Andover</option>
                                            <option value="Angmering"
            >
            Angmering</option>
                                            <option value="Annan"
            >
            Annan</option>
                                            <option value="Annfield Plain"
            >
            Annfield Plain</option>
                                            <option value="Anstey"
            >
            Anstey</option>
                                            <option value="Anstruther"
            >
            Anstruther</option>
                                            <option value="Antrim"
            >
            Antrim</option>
                                            <option value="Appledore"
            >
            Appledore</option>
                                            <option value="Appleton"
            >
            Appleton</option>
                                            <option value="Appley Bridge"
            >
            Appley Bridge</option>
                                            <option value="Arbroath"
            >
            Arbroath</option>
                                            <option value="Ardrossan"
            >
            Ardrossan</option>
                                            <option value="Arlesey"
            >
            Arlesey</option>
                                            <option value="Armadale"
            >
            Armadale</option>
                                            <option value="Armthorpe"
            >
            Armthorpe</option>
                                            <option value="Arnold"
            >
            Arnold</option>
                                            <option value="Arundel"
            >
            Arundel</option>
                                            <option value="Ascot"
            >
            Ascot</option>
                                            <option value="Ash Vale"
            >
            Ash Vale</option>
                                            <option value="Ashbourne"
            >
            Ashbourne</option>
                                            <option value="Ashburton"
            >
            Ashburton</option>
                                            <option value="Ashby de la Zouch"
            >
            Ashby de la Zouch</option>
                                            <option value="Ashford"
            >
            Ashford</option>
                                            <option value="Ashford"
            >
            Ashford</option>
                                            <option value="Ashfordby"
            >
            Ashfordby</option>
                                            <option value="Ashington"
            >
            Ashington</option>
                                            <option value="Ashtead"
            >
            Ashtead</option>
                                            <option value="Ashton"
            >
            Ashton</option>
                                            <option value="Ashton in Makerfield"
            >
            Ashton in Makerfield</option>
                                            <option value="Askern"
            >
            Askern</option>
                                            <option value="Aspatria"
            >
            Aspatria</option>
                                            <option value="Astley"
            >
            Astley</option>
                                            <option value="Aston Clinton"
            >
            Aston Clinton</option>
                                            <option value="Atherstone"
            >
            Atherstone</option>
                                            <option value="Atherton"
            >
            Atherton</option>
                                            <option value="Auchinleck"
            >
            Auchinleck</option>
                                            <option value="Auchterarder"
            >
            Auchterarder</option>
                                            <option value="Auckley"
            >
            Auckley</option>
                                            <option value="Audley"
            >
            Audley</option>
                                            <option value="Aughton"
            >
            Aughton</option>
                                            <option value="Aveley"
            >
            Aveley</option>
                                            <option value="Aviemore"
            >
            Aviemore</option>
                                            <option value="Avonmouth"
            >
            Avonmouth</option>
                                            <option value="Axminster"
            >
            Axminster</option>
                                            <option value="Aylesbury"
            >
            Aylesbury</option>
                                            <option value="Aylesford"
            >
            Aylesford</option>
                                            <option value="Aylestone"
            >
            Aylestone</option>
                                            <option value="Aylsham"
            >
            Aylsham</option>
                                            <option value="Ayr"
            >
            Ayr</option>
                                            <option value="Bacup"
            >
            Bacup</option>
                                            <option value="Badsey"
            >
            Badsey</option>
                                            <option value="Bagillt"
            >
            Bagillt</option>
                                            <option value="Bagshot"
            >
            Bagshot</option>
                                            <option value="Baguley"
            >
            Baguley</option>
                                            <option value="Baildon"
            >
            Baildon</option>
                                            <option value="Bakewell"
            >
            Bakewell</option>
                                            <option value="Balderton"
            >
            Balderton</option>
                                            <option value="Baldock"
            >
            Baldock</option>
                                            <option value="Balerno"
            >
            Balerno</option>
                                            <option value="Balham"
            >
            Balham</option>
                                            <option value="Ballingry"
            >
            Ballingry</option>
                                            <option value="Balloch"
            >
            Balloch</option>
                                            <option value="Bamber Bridge"
            >
            Bamber Bridge</option>
                                            <option value="Bampton"
            >
            Bampton</option>
                                            <option value="Banbridge"
            >
            Banbridge</option>
                                            <option value="Banbury"
            >
            Banbury</option>
                                            <option value="Banchory"
            >
            Banchory</option>
                                            <option value="Banff"
            >
            Banff</option>
                                            <option value="Bangor"
            >
            Bangor</option>
                                            <option value="Bangor"
            >
            Bangor</option>
                                            <option value="Banks"
            >
            Banks</option>
                                            <option value="Bannockburn"
            >
            Bannockburn</option>
                                            <option value="Banstead"
            >
            Banstead</option>
                                            <option value="Banwell"
            >
            Banwell</option>
                                            <option value="Bar Hill"
            >
            Bar Hill</option>
                                            <option value="Bare"
            >
            Bare</option>
                                            <option value="Bargoed"
            >
            Bargoed</option>
                                            <option value="Barkham"
            >
            Barkham</option>
                                            <option value="Barking"
            >
            Barking</option>
                                            <option value="Barlaston"
            >
            Barlaston</option>
                                            <option value="Barlborough"
            >
            Barlborough</option>
                                            <option value="Barmouth"
            >
            Barmouth</option>
                                            <option value="Barnard Castle"
            >
            Barnard Castle</option>
                                            <option value="Barnet"
            >
            Barnet</option>
                                            <option value="Barnoldswick"
            >
            Barnoldswick</option>
                                            <option value="Barnsley"
            >
            Barnsley</option>
                                            <option value="Barnstaple"
            >
            Barnstaple</option>
                                            <option value="Barrhead"
            >
            Barrhead</option>
                                            <option value="Barri"
            >
            Barri</option>
                                            <option value="Barrow in Furness"
            >
            Barrow in Furness</option>
                                            <option value="Barrow upon Humber"
            >
            Barrow upon Humber</option>
                                            <option value="Barrow upon Soar"
            >
            Barrow upon Soar</option>
                                            <option value="Barrowford"
            >
            Barrowford</option>
                                            <option value="Barton on Sea"
            >
            Barton on Sea</option>
                                            <option value="Barton under Needwood"
            >
            Barton under Needwood</option>
                                            <option value="Barton upon Humber"
            >
            Barton upon Humber</option>
                                            <option value="Barton upon Irwell"
            >
            Barton upon Irwell</option>
                                            <option value="Barton-le-Clay"
            >
            Barton-le-Clay</option>
                                            <option value="Barwell"
            >
            Barwell</option>
                                            <option value="Baschurch"
            >
            Baschurch</option>
                                            <option value="Basford"
            >
            Basford</option>
                                            <option value="Basildon"
            >
            Basildon</option>
                                            <option value="Basing"
            >
            Basing</option>
                                            <option value="Basingstoke"
            >
            Basingstoke</option>
                                            <option value="Bath"
            >
            Bath</option>
                                            <option value="Batheaston"
            >
            Batheaston</option>
                                            <option value="Bathgate"
            >
            Bathgate</option>
                                            <option value="Batley"
            >
            Batley</option>
                                            <option value="Battle"
            >
            Battle</option>
                                            <option value="Bawtry"
            >
            Bawtry</option>
                                            <option value="Baxenden"
            >
            Baxenden</option>
                                            <option value="Baystonhill"
            >
            Baystonhill</option>
                                            <option value="Beaconsfield"
            >
            Beaconsfield</option>
                                            <option value="Beaminster"
            >
            Beaminster</option>
                                            <option value="Bearsden"
            >
            Bearsden</option>
                                            <option value="Bearsted"
            >
            Bearsted</option>
                                            <option value="Beaufort"
            >
            Beaufort</option>
                                            <option value="Bebington"
            >
            Bebington</option>
                                            <option value="Beccles"
            >
            Beccles</option>
                                            <option value="Beckenham"
            >
            Beckenham</option>
                                            <option value="Bedale"
            >
            Bedale</option>
                                            <option value="Beddau"
            >
            Beddau</option>
                                            <option value="Bedford"
            >
            Bedford</option>
                                            <option value="Bedlington"
            >
            Bedlington</option>
                                            <option value="Bedwas"
            >
            Bedwas</option>
                                            <option value="Bedworth"
            >
            Bedworth</option>
                                            <option value="Beeston"
            >
            Beeston</option>
                                            <option value="Beith"
            >
            Beith</option>
                                            <option value="Belfast"
            >
            Belfast</option>
                                            <option value="Belgrave"
            >
            Belgrave</option>
                                            <option value="Belhelvie"
            >
            Belhelvie</option>
                                            <option value="Bellshill"
            >
            Bellshill</option>
                                            <option value="Belper"
            >
            Belper</option>
                                            <option value="Belton"
            >
            Belton</option>
                                            <option value="Bembridge"
            >
            Bembridge</option>
                                            <option value="Benllech"
            >
            Benllech</option>
                                            <option value="Benson"
            >
            Benson</option>
                                            <option value="Berkhampstead"
            >
            Berkhampstead</option>
                                            <option value="Berkswell"
            >
            Berkswell</option>
                                            <option value="Bersted"
            >
            Bersted</option>
                                            <option value="Berwick-Upon-Tweed"
            >
            Berwick-Upon-Tweed</option>
                                            <option value="Bethesda"
            >
            Bethesda</option>
                                            <option value="Beverley"
            >
            Beverley</option>
                                            <option value="Bewdley"
            >
            Bewdley</option>
                                            <option value="Bexhill"
            >
            Bexhill</option>
                                            <option value="Bexleyheath"
            >
            Bexleyheath</option>
                                            <option value="Bicester"
            >
            Bicester</option>
                                            <option value="Bickenhill"
            >
            Bickenhill</option>
                                            <option value="Bickley"
            >
            Bickley</option>
                                            <option value="Biddenden"
            >
            Biddenden</option>
                                            <option value="Biddenham"
            >
            Biddenham</option>
                                            <option value="Biddulph"
            >
            Biddulph</option>
                                            <option value="Bideford"
            >
            Bideford</option>
                                            <option value="Bidford-on-Avon"
            >
            Bidford-on-Avon</option>
                                            <option value="Bidston"
            >
            Bidston</option>
                                            <option value="Biggleswade"
            >
            Biggleswade</option>
                                            <option value="Billericay"
            >
            Billericay</option>
                                            <option value="Billinge"
            >
            Billinge</option>
                                            <option value="Billingham"
            >
            Billingham</option>
                                            <option value="Billingshurst"
            >
            Billingshurst</option>
                                            <option value="Bilsthorpe"
            >
            Bilsthorpe</option>
                                            <option value="Bilston"
            >
            Bilston</option>
                                            <option value="Bilton"
            >
            Bilton</option>
                                            <option value="Bilton"
            >
            Bilton</option>
                                            <option value="Binfield"
            >
            Binfield</option>
                                            <option value="Bingham"
            >
            Bingham</option>
                                            <option value="Bingley"
            >
            Bingley</option>
                                            <option value="Birchington"
            >
            Birchington</option>
                                            <option value="Birkenhead"
            >
            Birkenhead</option>
                                            <option value="Birmingham"
            >
            Birmingham</option>
                                            <option value="Birstall"
            >
            Birstall</option>
                                            <option value="Birstall"
            >
            Birstall</option>
                                            <option value="Birtley"
            >
            Birtley</option>
                                            <option value="Bishop Auckland"
            >
            Bishop Auckland</option>
                                            <option value="Bishopbriggs"
            >
            Bishopbriggs</option>
                                            <option value="Bishops Cleeve"
            >
            Bishops Cleeve</option>
                                            <option value="Bishops Lydeard"
            >
            Bishops Lydeard</option>
                                            <option value="Bishops Stortford"
            >
            Bishops Stortford</option>
                                            <option value="Bishops Waltham"
            >
            Bishops Waltham</option>
                                            <option value="Bishopstoke"
            >
            Bishopstoke</option>
                                            <option value="Bishopston"
            >
            Bishopston</option>
                                            <option value="Bishopthorpe"
            >
            Bishopthorpe</option>
                                            <option value="Bishopton"
            >
            Bishopton</option>
                                            <option value="Bisley"
            >
            Bisley</option>
                                            <option value="Bispham"
            >
            Bispham</option>
                                            <option value="Bitton"
            >
            Bitton</option>
                                            <option value="Blaby"
            >
            Blaby</option>
                                            <option value="Blackburn"
            >
            Blackburn</option>
                                            <option value="Blackburn"
            >
            Blackburn</option>
                                            <option value="Blackpool"
            >
            Blackpool</option>
                                            <option value="Blackrod"
            >
            Blackrod</option>
                                            <option value="Blackwell"
            >
            Blackwell</option>
                                            <option value="Blackwood"
            >
            Blackwood</option>
                                            <option value="Blaenau-Ffestiniog"
            >
            Blaenau-Ffestiniog</option>
                                            <option value="Blaenavon"
            >
            Blaenavon</option>
                                            <option value="Blaina"
            >
            Blaina</option>
                                            <option value="Blairgowrie"
            >
            Blairgowrie</option>
                                            <option value="Blandford Forum"
            >
            Blandford Forum</option>
                                            <option value="Blaydon"
            >
            Blaydon</option>
                                            <option value="Blean"
            >
            Blean</option>
                                            <option value="Bletchingley"
            >
            Bletchingley</option>
                                            <option value="Bletchley"
            >
            Bletchley</option>
                                            <option value="Blidworth"
            >
            Blidworth</option>
                                            <option value="Blofield"
            >
            Blofield</option>
                                            <option value="Bloxham"
            >
            Bloxham</option>
                                            <option value="Bloxwich"
            >
            Bloxwich</option>
                                            <option value="Blundellsands"
            >
            Blundellsands</option>
                                            <option value="Blunsdon Saint Andrew"
            >
            Blunsdon Saint Andrew</option>
                                            <option value="Blyth"
            >
            Blyth</option>
                                            <option value="Blythebridge"
            >
            Blythebridge</option>
                                            <option value="Bodmin"
            >
            Bodmin</option>
                                            <option value="Bognor Regis"
            >
            Bognor Regis</option>
                                            <option value="Bollington"
            >
            Bollington</option>
                                            <option value="Bolsover"
            >
            Bolsover</option>
                                            <option value="Bolton"
            >
            Bolton</option>
                                            <option value="Bolton le Sands"
            >
            Bolton le Sands</option>
                                            <option value="Bolton upon Dearne"
            >
            Bolton upon Dearne</option>
                                            <option value="Bonhill"
            >
            Bonhill</option>
                                            <option value="Bonnybridge"
            >
            Bonnybridge</option>
                                            <option value="Bonnyrigg"
            >
            Bonnyrigg</option>
                                            <option value="Bookham"
            >
            Bookham</option>
                                            <option value="Bootle"
            >
            Bootle</option>
                                            <option value="Boreham"
            >
            Boreham</option>
                                            <option value="Borehamwood"
            >
            Borehamwood</option>
                                            <option value="Borough Green"
            >
            Borough Green</option>
                                            <option value="Boroughbridge"
            >
            Boroughbridge</option>
                                            <option value="Boscombe"
            >
            Boscombe</option>
                                            <option value="Bosham"
            >
            Bosham</option>
                                            <option value="Boston"
            >
            Boston</option>
                                            <option value="Boston Spa"
            >
            Boston Spa</option>
                                            <option value="Bothwell"
            >
            Bothwell</option>
                                            <option value="Botley"
            >
            Botley</option>
                                            <option value="Bottesford"
            >
            Bottesford</option>
                                            <option value="Bottesford"
            >
            Bottesford</option>
                                            <option value="Boughton Monchelsea"
            >
            Boughton Monchelsea</option>
                                            <option value="Boultham"
            >
            Boultham</option>
                                            <option value="Bourne"
            >
            Bourne</option>
                                            <option value="Bourne End"
            >
            Bourne End</option>
                                            <option value="Bournemouth"
            >
            Bournemouth</option>
                                            <option value="Bournville"
            >
            Bournville</option>
                                            <option value="Bourton on the Water"
            >
            Bourton on the Water</option>
                                            <option value="Bovey Tracey"
            >
            Bovey Tracey</option>
                                            <option value="Bovingdon"
            >
            Bovingdon</option>
                                            <option value="Bowdon"
            >
            Bowdon</option>
                                            <option value="Bowling"
            >
            Bowling</option>
                                            <option value="Bowness-on-Windermere"
            >
            Bowness-on-Windermere</option>
                                            <option value="Box"
            >
            Box</option>
                                            <option value="Boxley"
            >
            Boxley</option>
                                            <option value="Bo’ness"
            >
            Bo’ness</option>
                                            <option value="Brackley"
            >
            Brackley</option>
                                            <option value="Bracknell"
            >
            Bracknell</option>
                                            <option value="Bradford-on-Avon"
            >
            Bradford-on-Avon</option>
                                            <option value="Bradwell"
            >
            Bradwell</option>
                                            <option value="Braintree"
            >
            Braintree</option>
                                            <option value="Bramcote"
            >
            Bramcote</option>
                                            <option value="Bramhall"
            >
            Bramhall</option>
                                            <option value="Bramhope"
            >
            Bramhope</option>
                                            <option value="Bramley"
            >
            Bramley</option>
                                            <option value="Bramley"
            >
            Bramley</option>
                                            <option value="Bramley"
            >
            Bramley</option>
                                            <option value="Brampton"
            >
            Brampton</option>
                                            <option value="Brampton"
            >
            Brampton</option>
                                            <option value="Brandon"
            >
            Brandon</option>
                                            <option value="Bransgore"
            >
            Bransgore</option>
                                            <option value="Branston"
            >
            Branston</option>
                                            <option value="Branston"
            >
            Branston</option>
                                            <option value="Braunstone"
            >
            Braunstone</option>
                                            <option value="Braunton"
            >
            Braunton</option>
                                            <option value="Bray"
            >
            Bray</option>
                                            <option value="Brayton"
            >
            Brayton</option>
                                            <option value="Breage"
            >
            Breage</option>
                                            <option value="Bream"
            >
            Bream</option>
                                            <option value="Brechin"
            >
            Brechin</option>
                                            <option value="Brecon"
            >
            Brecon</option>
                                            <option value="Bredbury"
            >
            Bredbury</option>
                                            <option value="Bredon"
            >
            Bredon</option>
                                            <option value="Brenchley"
            >
            Brenchley</option>
                                            <option value="Brentwood"
            >
            Brentwood</option>
                                            <option value="Brewood"
            >
            Brewood</option>
                                            <option value="Bridge of Allan"
            >
            Bridge of Allan</option>
                                            <option value="Bridge of Weir"
            >
            Bridge of Weir</option>
                                            <option value="Bridgnorth"
            >
            Bridgnorth</option>
                                            <option value="Bridgwater"
            >
            Bridgwater</option>
                                            <option value="Bridlington"
            >
            Bridlington</option>
                                            <option value="Bridport"
            >
            Bridport</option>
                                            <option value="Brierfield"
            >
            Brierfield</option>
                                            <option value="Brierley Hill"
            >
            Brierley Hill</option>
                                            <option value="Brigg"
            >
            Brigg</option>
                                            <option value="Brighouse"
            >
            Brighouse</option>
                                            <option value="Brightlingsea"
            >
            Brightlingsea</option>
                                            <option value="Brighton"
            >
            Brighton</option>
                                            <option value="Brimington"
            >
            Brimington</option>
                                            <option value="Brislington"
            >
            Brislington</option>
                                            <option value="Bristol"
            >
            Bristol</option>
                                            <option value="Briton Ferry"
            >
            Briton Ferry</option>
                                            <option value="Brixham"
            >
            Brixham</option>
                                            <option value="Brixton"
            >
            Brixton</option>
                                            <option value="Brixworth"
            >
            Brixworth</option>
                                            <option value="Broadstairs"
            >
            Broadstairs</option>
                                            <option value="Broadstone"
            >
            Broadstone</option>
                                            <option value="Broadwater"
            >
            Broadwater</option>
                                            <option value="Broadway"
            >
            Broadway</option>
                                            <option value="Brockenhurst"
            >
            Brockenhurst</option>
                                            <option value="Brockworth"
            >
            Brockworth</option>
                                            <option value="Brodsworth"
            >
            Brodsworth</option>
                                            <option value="Bromborough"
            >
            Bromborough</option>
                                            <option value="Bromham"
            >
            Bromham</option>
                                            <option value="Bromley"
            >
            Bromley</option>
                                            <option value="Bromsgrove"
            >
            Bromsgrove</option>
                                            <option value="Bromyard"
            >
            Bromyard</option>
                                            <option value="Brookwood"
            >
            Brookwood</option>
                                            <option value="Broomfield"
            >
            Broomfield</option>
                                            <option value="Broseley"
            >
            Broseley</option>
                                            <option value="Brotton"
            >
            Brotton</option>
                                            <option value="Broughton"
            >
            Broughton</option>
                                            <option value="Broughton"
            >
            Broughton</option>
                                            <option value="Broughton"
            >
            Broughton</option>
                                            <option value="Broughton Astley"
            >
            Broughton Astley</option>
                                            <option value="Broughty Ferry"
            >
            Broughty Ferry</option>
                                            <option value="Brownhills"
            >
            Brownhills</option>
                                            <option value="Broxbourne"
            >
            Broxbourne</option>
                                            <option value="Broxburn"
            >
            Broxburn</option>
                                            <option value="Brundall"
            >
            Brundall</option>
                                            <option value="Bruton"
            >
            Bruton</option>
                                            <option value="Brymbo"
            >
            Brymbo</option>
                                            <option value="Bryn"
            >
            Bryn</option>
                                            <option value="Brynamman"
            >
            Brynamman</option>
                                            <option value="Brynmawr"
            >
            Brynmawr</option>
                                            <option value="Brynna"
            >
            Brynna</option>
                                            <option value="Buckden"
            >
            Buckden</option>
                                            <option value="Buckfastleigh"
            >
            Buckfastleigh</option>
                                            <option value="Buckhaven"
            >
            Buckhaven</option>
                                            <option value="Buckhurst Hill"
            >
            Buckhurst Hill</option>
                                            <option value="Buckie"
            >
            Buckie</option>
                                            <option value="Buckingham"
            >
            Buckingham</option>
                                            <option value="Buckley"
            >
            Buckley</option>
                                            <option value="Bucksburn"
            >
            Bucksburn</option>
                                            <option value="Bude"
            >
            Bude</option>
                                            <option value="Budleigh Salterton"
            >
            Budleigh Salterton</option>
                                            <option value="Bugbrooke"
            >
            Bugbrooke</option>
                                            <option value="Bugle"
            >
            Bugle</option>
                                            <option value="Builth Wells"
            >
            Builth Wells</option>
                                            <option value="Bulford"
            >
            Bulford</option>
                                            <option value="Bulkington"
            >
            Bulkington</option>
                                            <option value="Bulwell"
            >
            Bulwell</option>
                                            <option value="Bungay"
            >
            Bungay</option>
                                            <option value="Buntingford"
            >
            Buntingford</option>
                                            <option value="Burbage"
            >
            Burbage</option>
                                            <option value="Burgess Hill"
            >
            Burgess Hill</option>
                                            <option value="Burghfield"
            >
            Burghfield</option>
                                            <option value="Burghill"
            >
            Burghill</option>
                                            <option value="Burley in Wharfedale"
            >
            Burley in Wharfedale</option>
                                            <option value="Burnham-on-Sea"
            >
            Burnham-on-Sea</option>
                                            <option value="Burnley"
            >
            Burnley</option>
                                            <option value="Burnopfield"
            >
            Burnopfield</option>
                                            <option value="Burntisland"
            >
            Burntisland</option>
                                            <option value="Burntwood"
            >
            Burntwood</option>
                                            <option value="Burscough"
            >
            Burscough</option>
                                            <option value="Bursledon"
            >
            Bursledon</option>
                                            <option value="Burslem"
            >
            Burslem</option>
                                            <option value="Burstow"
            >
            Burstow</option>
                                            <option value="Burton Joyce"
            >
            Burton Joyce</option>
                                            <option value="Burton Latimer"
            >
            Burton Latimer</option>
                                            <option value="Burton upon Stather"
            >
            Burton upon Stather</option>
                                            <option value="Burton upon Trent"
            >
            Burton upon Trent</option>
                                            <option value="Burtonwood"
            >
            Burtonwood</option>
                                            <option value="Burwash"
            >
            Burwash</option>
                                            <option value="Burwell"
            >
            Burwell</option>
                                            <option value="Bury"
            >
            Bury</option>
                                            <option value="Bury Saint Edmunds"
            >
            Bury Saint Edmunds</option>
                                            <option value="Bushbury"
            >
            Bushbury</option>
                                            <option value="Bushey"
            >
            Bushey</option>
                                            <option value="Buxted"
            >
            Buxted</option>
                                            <option value="Buxton"
            >
            Buxton</option>
                                            <option value="Byfleet"
            >
            Byfleet</option>
                                            <option value="Caddington"
            >
            Caddington</option>
                                            <option value="Caerdydd"
            >
            Caerdydd</option>
                                            <option value="Caerfyrddin"
            >
            Caerfyrddin</option>
                                            <option value="Caerleon"
            >
            Caerleon</option>
                                            <option value="Caernarfon"
            >
            Caernarfon</option>
                                            <option value="Caerphilly"
            >
            Caerphilly</option>
                                            <option value="Caister-on-Sea"
            >
            Caister-on-Sea</option>
                                            <option value="Caistor"
            >
            Caistor</option>
                                            <option value="Caldicot"
            >
            Caldicot</option>
                                            <option value="Callander"
            >
            Callander</option>
                                            <option value="Callington"
            >
            Callington</option>
                                            <option value="Calne"
            >
            Calne</option>
                                            <option value="Calstock"
            >
            Calstock</option>
                                            <option value="Calverley"
            >
            Calverley</option>
                                            <option value="Calverton"
            >
            Calverton</option>
                                            <option value="Cam"
            >
            Cam</option>
                                            <option value="Camberley"
            >
            Camberley</option>
                                            <option value="Camborne"
            >
            Camborne</option>
                                            <option value="Cambusbarron"
            >
            Cambusbarron</option>
                                            <option value="Cambuslang"
            >
            Cambuslang</option>
                                            <option value="Camelford"
            >
            Camelford</option>
                                            <option value="Campbeltown"
            >
            Campbeltown</option>
                                            <option value="Canford Cliffs"
            >
            Canford Cliffs</option>
                                            <option value="Cannock"
            >
            Cannock</option>
                                            <option value="Canterbury"
            >
            Canterbury</option>
                                            <option value="Cantley"
            >
            Cantley</option>
                                            <option value="Capel"
            >
            Capel</option>
                                            <option value="Capel Saint Mary"
            >
            Capel Saint Mary</option>
                                            <option value="Carcroft"
            >
            Carcroft</option>
                                            <option value="Cardenden"
            >
            Cardenden</option>
                                            <option value="Cardigan"
            >
            Cardigan</option>
                                            <option value="Carfin"
            >
            Carfin</option>
                                            <option value="Carisbrooke"
            >
            Carisbrooke</option>
                                            <option value="Carlisle"
            >
            Carlisle</option>
                                            <option value="Carlton"
            >
            Carlton</option>
                                            <option value="Carlton Colville"
            >
            Carlton Colville</option>
                                            <option value="Carluke"
            >
            Carluke</option>
                                            <option value="Carnforth"
            >
            Carnforth</option>
                                            <option value="Carnoustie"
            >
            Carnoustie</option>
                                            <option value="Carron"
            >
            Carron</option>
                                            <option value="Carshalton"
            >
            Carshalton</option>
                                            <option value="Carterton"
            >
            Carterton</option>
                                            <option value="Castle Bromwich"
            >
            Castle Bromwich</option>
                                            <option value="Castle Donnington"
            >
            Castle Donnington</option>
                                            <option value="Castle Douglas"
            >
            Castle Douglas</option>
                                            <option value="Castleford"
            >
            Castleford</option>
                                            <option value="Castleton"
            >
            Castleton</option>
                                            <option value="Caterham"
            >
            Caterham</option>
                                            <option value="Catford"
            >
            Catford</option>
                                            <option value="Catterick"
            >
            Catterick</option>
                                            <option value="Caversham"
            >
            Caversham</option>
                                            <option value="Chaddesden"
            >
            Chaddesden</option>
                                            <option value="Chailey"
            >
            Chailey</option>
                                            <option value="Chalfont Saint Giles"
            >
            Chalfont Saint Giles</option>
                                            <option value="Chalfont Saint Peter"
            >
            Chalfont Saint Peter</option>
                                            <option value="Chalford"
            >
            Chalford</option>
                                            <option value="Chalgrove"
            >
            Chalgrove</option>
                                            <option value="Chandlers Ford"
            >
            Chandlers Ford</option>
                                            <option value="Chapel en le Frith"
            >
            Chapel en le Frith</option>
                                            <option value="Chapel Saint Leonards"
            >
            Chapel Saint Leonards</option>
                                            <option value="Chapelhall"
            >
            Chapelhall</option>
                                            <option value="Chapeltown"
            >
            Chapeltown</option>
                                            <option value="Chard"
            >
            Chard</option>
                                            <option value="Charfield"
            >
            Charfield</option>
                                            <option value="Charing"
            >
            Charing</option>
                                            <option value="Charlbury"
            >
            Charlbury</option>
                                            <option value="Charlton Kings"
            >
            Charlton Kings</option>
                                            <option value="Charminster"
            >
            Charminster</option>
                                            <option value="Chartham"
            >
            Chartham</option>
                                            <option value="Chasetown"
            >
            Chasetown</option>
                                            <option value="Chatham"
            >
            Chatham</option>
                                            <option value="Chatteris"
            >
            Chatteris</option>
                                            <option value="Cheadle"
            >
            Cheadle</option>
                                            <option value="Cheadle"
            >
            Cheadle</option>
                                            <option value="Cheadle Hulme"
            >
            Cheadle Hulme</option>
                                            <option value="Cheam"
            >
            Cheam</option>
                                            <option value="Cheddar"
            >
            Cheddar</option>
                                            <option value="Cheddleton"
            >
            Cheddleton</option>
                                            <option value="Chellaston"
            >
            Chellaston</option>
                                            <option value="Chelmsford"
            >
            Chelmsford</option>
                                            <option value="Chelsfield"
            >
            Chelsfield</option>
                                            <option value="Cheltenham"
            >
            Cheltenham</option>
                                            <option value="Chepstow"
            >
            Chepstow</option>
                                            <option value="Cherry Hinton"
            >
            Cherry Hinton</option>
                                            <option value="Chertsey"
            >
            Chertsey</option>
                                            <option value="Chesham"
            >
            Chesham</option>
                                            <option value="Cheshunt"
            >
            Cheshunt</option>
                                            <option value="Chester"
            >
            Chester</option>
                                            <option value="Chester-le-Street"
            >
            Chester-le-Street</option>
                                            <option value="Chestfield"
            >
            Chestfield</option>
                                            <option value="Chichester"
            >
            Chichester</option>
                                            <option value="Chickerell"
            >
            Chickerell</option>
                                            <option value="Chiddingfold"
            >
            Chiddingfold</option>
                                            <option value="Chieveley"
            >
            Chieveley</option>
                                            <option value="Chigwell"
            >
            Chigwell</option>
                                            <option value="Childwall"
            >
            Childwall</option>
                                            <option value="Chilwell"
            >
            Chilwell</option>
                                            <option value="Chingford"
            >
            Chingford</option>
                                            <option value="Chinley"
            >
            Chinley</option>
                                            <option value="Chinnor"
            >
            Chinnor</option>
                                            <option value="Chippenham"
            >
            Chippenham</option>
                                            <option value="Chipping Norton"
            >
            Chipping Norton</option>
                                            <option value="Chipping Ongar"
            >
            Chipping Ongar</option>
                                            <option value="Chipping Sodbury"
            >
            Chipping Sodbury</option>
                                            <option value="Chipstead"
            >
            Chipstead</option>
                                            <option value="Chirk"
            >
            Chirk</option>
                                            <option value="Chisledon"
            >
            Chisledon</option>
                                            <option value="Chislehurst"
            >
            Chislehurst</option>
                                            <option value="Chobham"
            >
            Chobham</option>
                                            <option value="Cholsey"
            >
            Cholsey</option>
                                            <option value="Choppington"
            >
            Choppington</option>
                                            <option value="Chorley"
            >
            Chorley</option>
                                            <option value="Chorleywood"
            >
            Chorleywood</option>
                                            <option value="Christchurch"
            >
            Christchurch</option>
                                            <option value="Chryston"
            >
            Chryston</option>
                                            <option value="Chudleigh"
            >
            Chudleigh</option>
                                            <option value="Church"
            >
            Church</option>
                                            <option value="Church Gresley"
            >
            Church Gresley</option>
                                            <option value="Church Stretton"
            >
            Church Stretton</option>
                                            <option value="Churchdown"
            >
            Churchdown</option>
                                            <option value="Cilybebyll"
            >
            Cilybebyll</option>
                                            <option value="Cinderford"
            >
            Cinderford</option>
                                            <option value="Cirencester"
            >
            Cirencester</option>
                                            <option value="Clackmannan"
            >
            Clackmannan</option>
                                            <option value="Clacton-on-Sea"
            >
            Clacton-on-Sea</option>
                                            <option value="Clanfield"
            >
            Clanfield</option>
                                            <option value="Clapham"
            >
            Clapham</option>
                                            <option value="Clay Cross"
            >
            Clay Cross</option>
                                            <option value="Claygate"
            >
            Claygate</option>
                                            <option value="Clayton"
            >
            Clayton</option>
                                            <option value="Clayton le Moors"
            >
            Clayton le Moors</option>
                                            <option value="Cleator Moor"
            >
            Cleator Moor</option>
                                            <option value="Cleckheaton"
            >
            Cleckheaton</option>
                                            <option value="Cleethorpes"
            >
            Cleethorpes</option>
                                            <option value="Cleland"
            >
            Cleland</option>
                                            <option value="Clent"
            >
            Clent</option>
                                            <option value="Cleobury Mortimer"
            >
            Cleobury Mortimer</option>
                                            <option value="Clevedon"
            >
            Clevedon</option>
                                            <option value="Clifton"
            >
            Clifton</option>
                                            <option value="Clifton"
            >
            Clifton</option>
                                            <option value="Clifton"
            >
            Clifton</option>
                                            <option value="Cliftonville"
            >
            Cliftonville</option>
                                            <option value="Clipstone"
            >
            Clipstone</option>
                                            <option value="Clitheroe"
            >
            Clitheroe</option>
                                            <option value="Clowne"
            >
            Clowne</option>
                                            <option value="Clydach"
            >
            Clydach</option>
                                            <option value="Clydach Vale"
            >
            Clydach Vale</option>
                                            <option value="Clydebank"
            >
            Clydebank</option>
                                            <option value="Coalville"
            >
            Coalville</option>
                                            <option value="Coatbridge"
            >
            Coatbridge</option>
                                            <option value="Cobham"
            >
            Cobham</option>
                                            <option value="Cockenzie"
            >
            Cockenzie</option>
                                            <option value="Cockermouth"
            >
            Cockermouth</option>
                                            <option value="Codicote"
            >
            Codicote</option>
                                            <option value="Codsall"
            >
            Codsall</option>
                                            <option value="Coedpoeth"
            >
            Coedpoeth</option>
                                            <option value="Coggeshall"
            >
            Coggeshall</option>
                                            <option value="Colchester"
            >
            Colchester</option>
                                            <option value="Cold Ash"
            >
            Cold Ash</option>
                                            <option value="Coleford"
            >
            Coleford</option>
                                            <option value="Colerne"
            >
            Colerne</option>
                                            <option value="Coleshill"
            >
            Coleshill</option>
                                            <option value="Collingham"
            >
            Collingham</option>
                                            <option value="Colnbrook"
            >
            Colnbrook</option>
                                            <option value="Colne"
            >
            Colne</option>
                                            <option value="Colney Heath"
            >
            Colney Heath</option>
                                            <option value="Colwich"
            >
            Colwich</option>
                                            <option value="Colwyn Bay"
            >
            Colwyn Bay</option>
                                            <option value="Combe Martin"
            >
            Combe Martin</option>
                                            <option value="Congleton"
            >
            Congleton</option>
                                            <option value="Congresbury"
            >
            Congresbury</option>
                                            <option value="Coningsby"
            >
            Coningsby</option>
                                            <option value="Conisbrough"
            >
            Conisbrough</option>
                                            <option value="Connahs Quay"
            >
            Connahs Quay</option>
                                            <option value="Consett"
            >
            Consett</option>
                                            <option value="Conwy"
            >
            Conwy</option>
                                            <option value="Cookstown"
            >
            Cookstown</option>
                                            <option value="Copmanthorpe"
            >
            Copmanthorpe</option>
                                            <option value="Coppull"
            >
            Coppull</option>
                                            <option value="Corbridge"
            >
            Corbridge</option>
                                            <option value="Corby"
            >
            Corby</option>
                                            <option value="Corfe Mullen"
            >
            Corfe Mullen</option>
                                            <option value="Corsham"
            >
            Corsham</option>
                                            <option value="Cosby"
            >
            Cosby</option>
                                            <option value="Coseley"
            >
            Coseley</option>
                                            <option value="Cosham"
            >
            Cosham</option>
                                            <option value="Costessey"
            >
            Costessey</option>
                                            <option value="Cotgrave"
            >
            Cotgrave</option>
                                            <option value="Cottenham"
            >
            Cottenham</option>
                                            <option value="Cottingham"
            >
            Cottingham</option>
                                            <option value="Coulsdon"
            >
            Coulsdon</option>
                                            <option value="Coundon"
            >
            Coundon</option>
                                            <option value="Countesthorpe"
            >
            Countesthorpe</option>
                                            <option value="Cove"
            >
            Cove</option>
                                            <option value="Coventry"
            >
            Coventry</option>
                                            <option value="Cowbridge"
            >
            Cowbridge</option>
                                            <option value="Cowdenbeath"
            >
            Cowdenbeath</option>
                                            <option value="Cowes"
            >
            Cowes</option>
                                            <option value="Cowie"
            >
            Cowie</option>
                                            <option value="Cowley"
            >
            Cowley</option>
                                            <option value="Cowley"
            >
            Cowley</option>
                                            <option value="Cowpen"
            >
            Cowpen</option>
                                            <option value="Coxhoe"
            >
            Coxhoe</option>
                                            <option value="Cramlington"
            >
            Cramlington</option>
                                            <option value="Cramond"
            >
            Cramond</option>
                                            <option value="Cranbrook"
            >
            Cranbrook</option>
                                            <option value="Cranfield"
            >
            Cranfield</option>
                                            <option value="Cranleigh"
            >
            Cranleigh</option>
                                            <option value="Cranwell"
            >
            Cranwell</option>
                                            <option value="Crawley"
            >
            Crawley</option>
                                            <option value="Crayford"
            >
            Crayford</option>
                                            <option value="Crediton"
            >
            Crediton</option>
                                            <option value="Crewe"
            >
            Crewe</option>
                                            <option value="Crewkerne"
            >
            Crewkerne</option>
                                            <option value="Crich"
            >
            Crich</option>
                                            <option value="Cricklade"
            >
            Cricklade</option>
                                            <option value="Crieff"
            >
            Crieff</option>
                                            <option value="Crigglestone"
            >
            Crigglestone</option>
                                            <option value="Cringleford"
            >
            Cringleford</option>
                                            <option value="Croft"
            >
            Croft</option>
                                            <option value="Crofton"
            >
            Crofton</option>
                                            <option value="Cromer"
            >
            Cromer</option>
                                            <option value="Crosby"
            >
            Crosby</option>
                                            <option value="Cross Gates"
            >
            Cross Gates</option>
                                            <option value="Crosshouse"
            >
            Crosshouse</option>
                                            <option value="Crosskeys"
            >
            Crosskeys</option>
                                            <option value="Croston"
            >
            Croston</option>
                                            <option value="Crowan"
            >
            Crowan</option>
                                            <option value="Crowborough"
            >
            Crowborough</option>
                                            <option value="Crowland"
            >
            Crowland</option>
                                            <option value="Crowle"
            >
            Crowle</option>
                                            <option value="Crowthorne"
            >
            Crowthorne</option>
                                            <option value="Croxley Green"
            >
            Croxley Green</option>
                                            <option value="Croydon"
            >
            Croydon</option>
                                            <option value="Crumlin"
            >
            Crumlin</option>
                                            <option value="Cuckfield"
            >
            Cuckfield</option>
                                            <option value="Cuddington"
            >
            Cuddington</option>
                                            <option value="Cudworth"
            >
            Cudworth</option>
                                            <option value="Cuffley"
            >
            Cuffley</option>
                                            <option value="Culcheth"
            >
            Culcheth</option>
                                            <option value="Cullercoats"
            >
            Cullercoats</option>
                                            <option value="Cullingworth"
            >
            Cullingworth</option>
                                            <option value="Cullompton"
            >
            Cullompton</option>
                                            <option value="Cults"
            >
            Cults</option>
                                            <option value="Cumbernauld"
            >
            Cumbernauld</option>
                                            <option value="Cumnock"
            >
            Cumnock</option>
                                            <option value="Cumnor"
            >
            Cumnor</option>
                                            <option value="Cupar"
            >
            Cupar</option>
                                            <option value="Currie"
            >
            Currie</option>
                                            <option value="Cuxton"
            >
            Cuxton</option>
                                            <option value="Cwm"
            >
            Cwm</option>
                                            <option value="Cwmafan"
            >
            Cwmafan</option>
                                            <option value="Cwmbach"
            >
            Cwmbach</option>
                                            <option value="Cwmbran"
            >
            Cwmbran</option>
                                            <option value="Cymmer"
            >
            Cymmer</option>
                                            <option value="Dagenham"
            >
            Dagenham</option>
                                            <option value="Dalbeattie"
            >
            Dalbeattie</option>
                                            <option value="Dalkeith"
            >
            Dalkeith</option>
                                            <option value="Dalry"
            >
            Dalry</option>
                                            <option value="Dalston"
            >
            Dalston</option>
                                            <option value="Dalton in Furness"
            >
            Dalton in Furness</option>
                                            <option value="Danbury"
            >
            Danbury</option>
                                            <option value="Darenth"
            >
            Darenth</option>
                                            <option value="Darfield"
            >
            Darfield</option>
                                            <option value="Darlaston"
            >
            Darlaston</option>
                                            <option value="Darlington"
            >
            Darlington</option>
                                            <option value="Dartmouth"
            >
            Dartmouth</option>
                                            <option value="Darton"
            >
            Darton</option>
                                            <option value="Darvel"
            >
            Darvel</option>
                                            <option value="Darwen"
            >
            Darwen</option>
                                            <option value="Datchet"
            >
            Datchet</option>
                                            <option value="Davenham"
            >
            Davenham</option>
                                            <option value="Daventry"
            >
            Daventry</option>
                                            <option value="Davyhulme"
            >
            Davyhulme</option>
                                            <option value="Dawlish"
            >
            Dawlish</option>
                                            <option value="Deal"
            >
            Deal</option>
                                            <option value="Deanshanger"
            >
            Deanshanger</option>
                                            <option value="Deeping Saint James"
            >
            Deeping Saint James</option>
                                            <option value="Deganwy"
            >
            Deganwy</option>
                                            <option value="Delabole"
            >
            Delabole</option>
                                            <option value="Denby Dale"
            >
            Denby Dale</option>
                                            <option value="Denham"
            >
            Denham</option>
                                            <option value="Denholme"
            >
            Denholme</option>
                                            <option value="Denny"
            >
            Denny</option>
                                            <option value="Denton"
            >
            Denton</option>
                                            <option value="Derby"
            >
            Derby</option>
                                            <option value="Derry"
            >
            Derry</option>
                                            <option value="Dersingham"
            >
            Dersingham</option>
                                            <option value="Desborough"
            >
            Desborough</option>
                                            <option value="Desford"
            >
            Desford</option>
                                            <option value="Devizes"
            >
            Devizes</option>
                                            <option value="Dewsbury"
            >
            Dewsbury</option>
                                            <option value="Deysbrook"
            >
            Deysbrook</option>
                                            <option value="Didcot"
            >
            Didcot</option>
                                            <option value="Dinas Powys"
            >
            Dinas Powys</option>
                                            <option value="Dingle"
            >
            Dingle</option>
                                            <option value="Dingwall"
            >
            Dingwall</option>
                                            <option value="Dinnington"
            >
            Dinnington</option>
                                            <option value="Disley"
            >
            Disley</option>
                                            <option value="Diss"
            >
            Diss</option>
                                            <option value="Dobwalls"
            >
            Dobwalls</option>
                                            <option value="Dodworth"
            >
            Dodworth</option>
                                            <option value="Dolgellau"
            >
            Dolgellau</option>
                                            <option value="Dollar"
            >
            Dollar</option>
                                            <option value="Dollis Hill"
            >
            Dollis Hill</option>
                                            <option value="Doncaster"
            >
            Doncaster</option>
                                            <option value="Donington"
            >
            Donington</option>
                                            <option value="Dorchester"
            >
            Dorchester</option>
                                            <option value="Dore"
            >
            Dore</option>
                                            <option value="Dorking"
            >
            Dorking</option>
                                            <option value="Dornoch"
            >
            Dornoch</option>
                                            <option value="Dorridge"
            >
            Dorridge</option>
                                            <option value="Dover"
            >
            Dover</option>
                                            <option value="Dowlais"
            >
            Dowlais</option>
                                            <option value="Downham Market"
            >
            Downham Market</option>
                                            <option value="Downside"
            >
            Downside</option>
                                            <option value="Downton"
            >
            Downton</option>
                                            <option value="Draycott"
            >
            Draycott</option>
                                            <option value="Drayton"
            >
            Drayton</option>
                                            <option value="Dreghorn"
            >
            Dreghorn</option>
                                            <option value="Drighlington"
            >
            Drighlington</option>
                                            <option value="Droitwich"
            >
            Droitwich</option>
                                            <option value="Dronfield"
            >
            Dronfield</option>
                                            <option value="Droylsden"
            >
            Droylsden</option>
                                            <option value="Drybrook"
            >
            Drybrook</option>
                                            <option value="Dudley"
            >
            Dudley</option>
                                            <option value="Duffield"
            >
            Duffield</option>
                                            <option value="Dukinfield"
            >
            Dukinfield</option>
                                            <option value="Dumbarton"
            >
            Dumbarton</option>
                                            <option value="Dumfries"
            >
            Dumfries</option>
                                            <option value="Dunbar"
            >
            Dunbar</option>
                                            <option value="Dunblane"
            >
            Dunblane</option>
                                            <option value="Dunchurch"
            >
            Dunchurch</option>
                                            <option value="Dundee"
            >
            Dundee</option>
                                            <option value="Dundonald"
            >
            Dundonald</option>
                                            <option value="Dunfermline"
            >
            Dunfermline</option>
                                            <option value="Dunoon"
            >
            Dunoon</option>
                                            <option value="Duns"
            >
            Duns</option>
                                            <option value="Dunstable"
            >
            Dunstable</option>
                                            <option value="Duntocher"
            >
            Duntocher</option>
                                            <option value="Durham"
            >
            Durham</option>
                                            <option value="Durrington"
            >
            Durrington</option>
                                            <option value="Dursley"
            >
            Dursley</option>
                                            <option value="Dyce"
            >
            Dyce</option>
                                            <option value="Dymchurch"
            >
            Dymchurch</option>
                                            <option value="Eaglesham"
            >
            Eaglesham</option>
                                            <option value="Ealing"
            >
            Ealing</option>
                                            <option value="Earby"
            >
            Earby</option>
                                            <option value="Earlestown"
            >
            Earlestown</option>
                                            <option value="Earley"
            >
            Earley</option>
                                            <option value="Earls Barton"
            >
            Earls Barton</option>
                                            <option value="Earls Colne"
            >
            Earls Colne</option>
                                            <option value="Earlswood"
            >
            Earlswood</option>
                                            <option value="Easington"
            >
            Easington</option>
                                            <option value="Easingwold"
            >
            Easingwold</option>
                                            <option value="East Barnet"
            >
            East Barnet</option>
                                            <option value="East Bergholt"
            >
            East Bergholt</option>
                                            <option value="East Calder"
            >
            East Calder</option>
                                            <option value="East Cowes"
            >
            East Cowes</option>
                                            <option value="East Dereham"
            >
            East Dereham</option>
                                            <option value="East Finchley"
            >
            East Finchley</option>
                                            <option value="East Grinstead"
            >
            East Grinstead</option>
                                            <option value="East Ham"
            >
            East Ham</option>
                                            <option value="East Horsley"
            >
            East Horsley</option>
                                            <option value="East Kilbride"
            >
            East Kilbride</option>
                                            <option value="East Leake"
            >
            East Leake</option>
                                            <option value="East Peckham"
            >
            East Peckham</option>
                                            <option value="East Preston"
            >
            East Preston</option>
                                            <option value="East Retford"
            >
            East Retford</option>
                                            <option value="East Wittering"
            >
            East Wittering</option>
                                            <option value="East Woodhay"
            >
            East Woodhay</option>
                                            <option value="Eastbourne"
            >
            Eastbourne</option>
                                            <option value="Eastchurch"
            >
            Eastchurch</option>
                                            <option value="Eastergate"
            >
            Eastergate</option>
                                            <option value="Eastham"
            >
            Eastham</option>
                                            <option value="Eastleigh"
            >
            Eastleigh</option>
                                            <option value="Easton-in-Gordano"
            >
            Easton-in-Gordano</option>
                                            <option value="Eastwood"
            >
            Eastwood</option>
                                            <option value="Eaton Socon"
            >
            Eaton Socon</option>
                                            <option value="Ebbw Vale"
            >
            Ebbw Vale</option>
                                            <option value="Eccles"
            >
            Eccles</option>
                                            <option value="Ecclesall"
            >
            Ecclesall</option>
                                            <option value="Ecclesfield"
            >
            Ecclesfield</option>
                                            <option value="Eccleshall"
            >
            Eccleshall</option>
                                            <option value="Eccleston"
            >
            Eccleston</option>
                                            <option value="Eccleston"
            >
            Eccleston</option>
                                            <option value="Eckington"
            >
            Eckington</option>
                                            <option value="Edenbridge"
            >
            Edenbridge</option>
                                            <option value="Edgware"
            >
            Edgware</option>
                                            <option value="Edinburgh"
            >
            Edinburgh</option>
                                            <option value="Edmonton"
            >
            Edmonton</option>
                                            <option value="Edwalton"
            >
            Edwalton</option>
                                            <option value="Edwinstowe"
            >
            Edwinstowe</option>
                                            <option value="Effingham"
            >
            Effingham</option>
                                            <option value="Egg Buckland"
            >
            Egg Buckland</option>
                                            <option value="Egham"
            >
            Egham</option>
                                            <option value="Egremont"
            >
            Egremont</option>
                                            <option value="Elderslie"
            >
            Elderslie</option>
                                            <option value="Elgin"
            >
            Elgin</option>
                                            <option value="Elland"
            >
            Elland</option>
                                            <option value="Ellesmere"
            >
            Ellesmere</option>
                                            <option value="Ellesmere Port"
            >
            Ellesmere Port</option>
                                            <option value="Ellington"
            >
            Ellington</option>
                                            <option value="Ellon"
            >
            Ellon</option>
                                            <option value="Elm"
            >
            Elm</option>
                                            <option value="Elmswell"
            >
            Elmswell</option>
                                            <option value="Elsecar"
            >
            Elsecar</option>
                                            <option value="Elstead"
            >
            Elstead</option>
                                            <option value="Elstow"
            >
            Elstow</option>
                                            <option value="Elstree"
            >
            Elstree</option>
                                            <option value="Eltham"
            >
            Eltham</option>
                                            <option value="Elton"
            >
            Elton</option>
                                            <option value="Ely"
            >
            Ely</option>
                                            <option value="Emneth"
            >
            Emneth</option>
                                            <option value="Emsworth"
            >
            Emsworth</option>
                                            <option value="Enderby"
            >
            Enderby</option>
                                            <option value="Endon"
            >
            Endon</option>
                                            <option value="Enfield"
            >
            Enfield</option>
                                            <option value="Enfield Lock"
            >
            Enfield Lock</option>
                                            <option value="Englefield Green"
            >
            Englefield Green</option>
                                            <option value="Epping"
            >
            Epping</option>
                                            <option value="Epsom"
            >
            Epsom</option>
                                            <option value="Epworth"
            >
            Epworth</option>
                                            <option value="Erdington"
            >
            Erdington</option>
                                            <option value="Eriswell"
            >
            Eriswell</option>
                                            <option value="Erith"
            >
            Erith</option>
                                            <option value="Esher"
            >
            Esher</option>
                                            <option value="Eston"
            >
            Eston</option>
                                            <option value="Etwall"
            >
            Etwall</option>
                                            <option value="Euxton"
            >
            Euxton</option>
                                            <option value="Evesham"
            >
            Evesham</option>
                                            <option value="Evington"
            >
            Evington</option>
                                            <option value="Ewell"
            >
            Ewell</option>
                                            <option value="Exeter"
            >
            Exeter</option>
                                            <option value="Exhall"
            >
            Exhall</option>
                                            <option value="Exmouth"
            >
            Exmouth</option>
                                            <option value="Eyemouth"
            >
            Eyemouth</option>
                                            <option value="Eynsham"
            >
            Eynsham</option>
                                            <option value="Eythorne"
            >
            Eythorne</option>
                                            <option value="Failsworth"
            >
            Failsworth</option>
                                            <option value="Fairford"
            >
            Fairford</option>
                                            <option value="Fakenham"
            >
            Fakenham</option>
                                            <option value="Falkirk"
            >
            Falkirk</option>
                                            <option value="Falmouth"
            >
            Falmouth</option>
                                            <option value="Fareham"
            >
            Fareham</option>
                                            <option value="Faringdon"
            >
            Faringdon</option>
                                            <option value="Farington"
            >
            Farington</option>
                                            <option value="Farnborough"
            >
            Farnborough</option>
                                            <option value="Farnborough"
            >
            Farnborough</option>
                                            <option value="Farnham"
            >
            Farnham</option>
                                            <option value="Farnham Royal"
            >
            Farnham Royal</option>
                                            <option value="Farnley"
            >
            Farnley</option>
                                            <option value="Farnsfield"
            >
            Farnsfield</option>
                                            <option value="Farnworth"
            >
            Farnworth</option>
                                            <option value="Fauldhouse"
            >
            Fauldhouse</option>
                                            <option value="Faversham"
            >
            Faversham</option>
                                            <option value="Fazakerley"
            >
            Fazakerley</option>
                                            <option value="Fazeley"
            >
            Fazeley</option>
                                            <option value="Featherstone"
            >
            Featherstone</option>
                                            <option value="Felixstowe"
            >
            Felixstowe</option>
                                            <option value="Felling"
            >
            Felling</option>
                                            <option value="Felpham"
            >
            Felpham</option>
                                            <option value="Felsted"
            >
            Felsted</option>
                                            <option value="Feltham"
            >
            Feltham</option>
                                            <option value="Felton"
            >
            Felton</option>
                                            <option value="Feltwell"
            >
            Feltwell</option>
                                            <option value="Fen Stanton"
            >
            Fen Stanton</option>
                                            <option value="Fenton"
            >
            Fenton</option>
                                            <option value="Feock"
            >
            Feock</option>
                                            <option value="Fern Down"
            >
            Fern Down</option>
                                            <option value="Ferndale"
            >
            Ferndale</option>
                                            <option value="Fernhurst"
            >
            Fernhurst</option>
                                            <option value="Ferring"
            >
            Ferring</option>
                                            <option value="Ferryhill"
            >
            Ferryhill</option>
                                            <option value="Ffestiniog"
            >
            Ffestiniog</option>
                                            <option value="Filey"
            >
            Filey</option>
                                            <option value="Finchley"
            >
            Finchley</option>
                                            <option value="Finedon"
            >
            Finedon</option>
                                            <option value="Fishburn"
            >
            Fishburn</option>
                                            <option value="Fishguard"
            >
            Fishguard</option>
                                            <option value="Fishtoft"
            >
            Fishtoft</option>
                                            <option value="Fleet"
            >
            Fleet</option>
                                            <option value="Fleetwood"
            >
            Fleetwood</option>
                                            <option value="Flint"
            >
            Flint</option>
                                            <option value="Flitwick"
            >
            Flitwick</option>
                                            <option value="Flixton"
            >
            Flixton</option>
                                            <option value="Folkestone"
            >
            Folkestone</option>
                                            <option value="Fordham"
            >
            Fordham</option>
                                            <option value="Fordingbridge"
            >
            Fordingbridge</option>
                                            <option value="Forest Row"
            >
            Forest Row</option>
                                            <option value="Forfar"
            >
            Forfar</option>
                                            <option value="Formby"
            >
            Formby</option>
                                            <option value="Forres"
            >
            Forres</option>
                                            <option value="Fort William"
            >
            Fort William</option>
                                            <option value="Four Marks"
            >
            Four Marks</option>
                                            <option value="Framlingham"
            >
            Framlingham</option>
                                            <option value="Frampton Cotterell"
            >
            Frampton Cotterell</option>
                                            <option value="Fraserburgh"
            >
            Fraserburgh</option>
                                            <option value="Freckleton"
            >
            Freckleton</option>
                                            <option value="Fremington"
            >
            Fremington</option>
                                            <option value="Freshwater"
            >
            Freshwater</option>
                                            <option value="Friern Barnet"
            >
            Friern Barnet</option>
                                            <option value="Frimley"
            >
            Frimley</option>
                                            <option value="Frizington"
            >
            Frizington</option>
                                            <option value="Frodsham"
            >
            Frodsham</option>
                                            <option value="Frome"
            >
            Frome</option>
                                            <option value="Fulbourn"
            >
            Fulbourn</option>
                                            <option value="Fulford"
            >
            Fulford</option>
                                            <option value="Fulham"
            >
            Fulham</option>
                                            <option value="Fulwood"
            >
            Fulwood</option>
                                            <option value="Fyfield"
            >
            Fyfield</option>
                                            <option value="Gainsborough"
            >
            Gainsborough</option>
                                            <option value="Galashiels"
            >
            Galashiels</option>
                                            <option value="Galleywood"
            >
            Galleywood</option>
                                            <option value="Galston"
            >
            Galston</option>
                                            <option value="Gamlingay"
            >
            Gamlingay</option>
                                            <option value="Garelochhead"
            >
            Garelochhead</option>
                                            <option value="Garforth"
            >
            Garforth</option>
                                            <option value="Garlinge"
            >
            Garlinge</option>
                                            <option value="Garstang"
            >
            Garstang</option>
                                            <option value="Garswood"
            >
            Garswood</option>
                                            <option value="Gateshead"
            >
            Gateshead</option>
                                            <option value="Gedling"
            >
            Gedling</option>
                                            <option value="Giffnock"
            >
            Giffnock</option>
                                            <option value="Gilberdike"
            >
            Gilberdike</option>
                                            <option value="Gildersome"
            >
            Gildersome</option>
                                            <option value="Gilfach Goch"
            >
            Gilfach Goch</option>
                                            <option value="Gillingham"
            >
            Gillingham</option>
                                            <option value="Gillingham"
            >
            Gillingham</option>
                                            <option value="Girton"
            >
            Girton</option>
                                            <option value="Girvan"
            >
            Girvan</option>
                                            <option value="Glascote"
            >
            Glascote</option>
                                            <option value="Glasgow"
            >
            Glasgow</option>
                                            <option value="Glastonbury"
            >
            Glastonbury</option>
                                            <option value="Glemsford"
            >
            Glemsford</option>
                                            <option value="Glen Parva"
            >
            Glen Parva</option>
                                            <option value="Glenboig"
            >
            Glenboig</option>
                                            <option value="Glenfield"
            >
            Glenfield</option>
                                            <option value="Glenrothes"
            >
            Glenrothes</option>
                                            <option value="Glossop"
            >
            Glossop</option>
                                            <option value="Glyncorrwg"
            >
            Glyncorrwg</option>
                                            <option value="Glynneath"
            >
            Glynneath</option>
                                            <option value="Gnosall"
            >
            Gnosall</option>
                                            <option value="Gobowen"
            >
            Gobowen</option>
                                            <option value="Godalming"
            >
            Godalming</option>
                                            <option value="Godmanchester"
            >
            Godmanchester</option>
                                            <option value="Godstone"
            >
            Godstone</option>
                                            <option value="Goffs Oak"
            >
            Goffs Oak</option>
                                            <option value="Golborne"
            >
            Golborne</option>
                                            <option value="Golcar"
            >
            Golcar</option>
                                            <option value="Goodmayes"
            >
            Goodmayes</option>
                                            <option value="Goole"
            >
            Goole</option>
                                            <option value="Gorebridge"
            >
            Gorebridge</option>
                                            <option value="Goring by Sea"
            >
            Goring by Sea</option>
                                            <option value="Gorleston-on-Sea"
            >
            Gorleston-on-Sea</option>
                                            <option value="Gornalwood"
            >
            Gornalwood</option>
                                            <option value="Gosberton"
            >
            Gosberton</option>
                                            <option value="Gosforth"
            >
            Gosforth</option>
                                            <option value="Gosport"
            >
            Gosport</option>
                                            <option value="Goudhurst"
            >
            Goudhurst</option>
                                            <option value="Gourock"
            >
            Gourock</option>
                                            <option value="Govan"
            >
            Govan</option>
                                            <option value="Gowerton"
            >
            Gowerton</option>
                                            <option value="Grangemouth"
            >
            Grangemouth</option>
                                            <option value="Grangetown"
            >
            Grangetown</option>
                                            <option value="Grantham"
            >
            Grantham</option>
                                            <option value="Gravesend"
            >
            Gravesend</option>
                                            <option value="Grays"
            >
            Grays</option>
                                            <option value="Great Ayton"
            >
            Great Ayton</option>
                                            <option value="Great Baddow"
            >
            Great Baddow</option>
                                            <option value="Great Billing"
            >
            Great Billing</option>
                                            <option value="Great Bookham"
            >
            Great Bookham</option>
                                            <option value="Great Burstead"
            >
            Great Burstead</option>
                                            <option value="Great Chart"
            >
            Great Chart</option>
                                            <option value="Great Cornard"
            >
            Great Cornard</option>
                                            <option value="Great Driffield"
            >
            Great Driffield</option>
                                            <option value="Great Dunmow"
            >
            Great Dunmow</option>
                                            <option value="Great Harwood"
            >
            Great Harwood</option>
                                            <option value="Great Linford"
            >
            Great Linford</option>
                                            <option value="Great Meols"
            >
            Great Meols</option>
                                            <option value="Great Missenden"
            >
            Great Missenden</option>
                                            <option value="Great Sankey"
            >
            Great Sankey</option>
                                            <option value="Great Totham"
            >
            Great Totham</option>
                                            <option value="Great Wakering"
            >
            Great Wakering</option>
                                            <option value="Great Warley Street"
            >
            Great Warley Street</option>
                                            <option value="Great Wyrley"
            >
            Great Wyrley</option>
                                            <option value="Great Yarmouth"
            >
            Great Yarmouth</option>
                                            <option value="Greenfield"
            >
            Greenfield</option>
                                            <option value="Greenford"
            >
            Greenford</option>
                                            <option value="Greenhill"
            >
            Greenhill</option>
                                            <option value="Greenhithe"
            >
            Greenhithe</option>
                                            <option value="Greenock"
            >
            Greenock</option>
                                            <option value="Greetland"
            >
            Greetland</option>
                                            <option value="Grenoside"
            >
            Grenoside</option>
                                            <option value="Gresford"
            >
            Gresford</option>
                                            <option value="Gretna"
            >
            Gretna</option>
                                            <option value="Griffithstown"
            >
            Griffithstown</option>
                                            <option value="Grimsargh"
            >
            Grimsargh</option>
                                            <option value="Grimsby"
            >
            Grimsby</option>
                                            <option value="Grove"
            >
            Grove</option>
                                            <option value="Guildford"
            >
            Guildford</option>
                                            <option value="Guisborough"
            >
            Guisborough</option>
                                            <option value="Guiseley"
            >
            Guiseley</option>
                                            <option value="Gullane"
            >
            Gullane</option>
                                            <option value="Gwithian"
            >
            Gwithian</option>
                                            <option value="Hackney"
            >
            Hackney</option>
                                            <option value="Haddenham"
            >
            Haddenham</option>
                                            <option value="Haddenham"
            >
            Haddenham</option>
                                            <option value="Haddington"
            >
            Haddington</option>
                                            <option value="Hadfield"
            >
            Hadfield</option>
                                            <option value="Hadleigh"
            >
            Hadleigh</option>
                                            <option value="Hadleigh"
            >
            Hadleigh</option>
                                            <option value="Hadlow"
            >
            Hadlow</option>
                                            <option value="Hagley"
            >
            Hagley</option>
                                            <option value="Hailsham"
            >
            Hailsham</option>
                                            <option value="Halesowen"
            >
            Halesowen</option>
                                            <option value="Halesworth"
            >
            Halesworth</option>
                                            <option value="Halewood"
            >
            Halewood</option>
                                            <option value="Halifax"
            >
            Halifax</option>
                                            <option value="Halkyn"
            >
            Halkyn</option>
                                            <option value="Halling"
            >
            Halling</option>
                                            <option value="Halstead"
            >
            Halstead</option>
                                            <option value="Haltwhistle"
            >
            Haltwhistle</option>
                                            <option value="Hambleton"
            >
            Hambleton</option>
                                            <option value="Hamilton"
            >
            Hamilton</option>
                                            <option value="Hampton"
            >
            Hampton</option>
                                            <option value="Hamworthy"
            >
            Hamworthy</option>
                                            <option value="Handforth"
            >
            Handforth</option>
                                            <option value="Handsworth"
            >
            Handsworth</option>
                                            <option value="Hanwell"
            >
            Hanwell</option>
                                            <option value="Harbledown"
            >
            Harbledown</option>
                                            <option value="Harborne"
            >
            Harborne</option>
                                            <option value="Hardwick"
            >
            Hardwick</option>
                                            <option value="Hardwicke"
            >
            Hardwicke</option>
                                            <option value="Harefield"
            >
            Harefield</option>
                                            <option value="Harewood"
            >
            Harewood</option>
                                            <option value="Harlescott"
            >
            Harlescott</option>
                                            <option value="Harleston"
            >
            Harleston</option>
                                            <option value="Harlington"
            >
            Harlington</option>
                                            <option value="Harpenden"
            >
            Harpenden</option>
                                            <option value="Harrington"
            >
            Harrington</option>
                                            <option value="Harrogate"
            >
            Harrogate</option>
                                            <option value="Harrow"
            >
            Harrow</option>
                                            <option value="Harrow on the Hill"
            >
            Harrow on the Hill</option>
                                            <option value="Harrow Weald"
            >
            Harrow Weald</option>
                                            <option value="Hartford"
            >
            Hartford</option>
                                            <option value="Harthill"
            >
            Harthill</option>
                                            <option value="Hartlebury"
            >
            Hartlebury</option>
                                            <option value="Hartlepool"
            >
            Hartlepool</option>
                                            <option value="Hartley"
            >
            Hartley</option>
                                            <option value="Harton"
            >
            Harton</option>
                                            <option value="Hartshorne"
            >
            Hartshorne</option>
                                            <option value="Harwich"
            >
            Harwich</option>
                                            <option value="Harworth"
            >
            Harworth</option>
                                            <option value="Haslemere"
            >
            Haslemere</option>
                                            <option value="Haslingden"
            >
            Haslingden</option>
                                            <option value="Hassocks"
            >
            Hassocks</option>
                                            <option value="Hastings"
            >
            Hastings</option>
                                            <option value="Hatch End"
            >
            Hatch End</option>
                                            <option value="Hatfield"
            >
            Hatfield</option>
                                            <option value="Hatfield"
            >
            Hatfield</option>
                                            <option value="Hatfield Peverel"
            >
            Hatfield Peverel</option>
                                            <option value="Havant"
            >
            Havant</option>
                                            <option value="Haven Street"
            >
            Haven Street</option>
                                            <option value="Haverhill"
            >
            Haverhill</option>
                                            <option value="Hawick"
            >
            Hawick</option>
                                            <option value="Hawkhurst"
            >
            Hawkhurst</option>
                                            <option value="Hawkinge"
            >
            Hawkinge</option>
                                            <option value="Haworth"
            >
            Haworth</option>
                                            <option value="Haxby"
            >
            Haxby</option>
                                            <option value="Haxey"
            >
            Haxey</option>
                                            <option value="Haydock"
            >
            Haydock</option>
                                            <option value="Hayes"
            >
            Hayes</option>
                                            <option value="Hayes"
            >
            Hayes</option>
                                            <option value="Hayfield"
            >
            Hayfield</option>
                                            <option value="Hayle"
            >
            Hayle</option>
                                            <option value="Haywards Heath"
            >
            Haywards Heath</option>
                                            <option value="Hazel Grove"
            >
            Hazel Grove</option>
                                            <option value="Heacham"
            >
            Heacham</option>
                                            <option value="Headcorn"
            >
            Headcorn</option>
                                            <option value="Headley"
            >
            Headley</option>
                                            <option value="Heage"
            >
            Heage</option>
                                            <option value="Healing"
            >
            Healing</option>
                                            <option value="Heanor"
            >
            Heanor</option>
                                            <option value="Heath"
            >
            Heath</option>
                                            <option value="Hebburn"
            >
            Hebburn</option>
                                            <option value="Heckington"
            >
            Heckington</option>
                                            <option value="Heckmondwike"
            >
            Heckmondwike</option>
                                            <option value="Hednesford"
            >
            Hednesford</option>
                                            <option value="Hedon"
            >
            Hedon</option>
                                            <option value="Heighington"
            >
            Heighington</option>
                                            <option value="Helensburgh"
            >
            Helensburgh</option>
                                            <option value="Hellesdon"
            >
            Hellesdon</option>
                                            <option value="Helmshore"
            >
            Helmshore</option>
                                            <option value="Helsby"
            >
            Helsby</option>
                                            <option value="Helston"
            >
            Helston</option>
                                            <option value="Hemel Hempstead"
            >
            Hemel Hempstead</option>
                                            <option value="Hemingford Grey"
            >
            Hemingford Grey</option>
                                            <option value="Hemsby"
            >
            Hemsby</option>
                                            <option value="Hemsworth"
            >
            Hemsworth</option>
                                            <option value="Hendon"
            >
            Hendon</option>
                                            <option value="Hendy"
            >
            Hendy</option>
                                            <option value="Henfield"
            >
            Henfield</option>
                                            <option value="Hengoed"
            >
            Hengoed</option>
                                            <option value="Henley on Thames"
            >
            Henley on Thames</option>
                                            <option value="Hereford"
            >
            Hereford</option>
                                            <option value="Herne"
            >
            Herne</option>
                                            <option value="Herne Bay"
            >
            Herne Bay</option>
                                            <option value="Hersham"
            >
            Hersham</option>
                                            <option value="Herstmonceux"
            >
            Herstmonceux</option>
                                            <option value="Hertford"
            >
            Hertford</option>
                                            <option value="Hesketh Bank"
            >
            Hesketh Bank</option>
                                            <option value="Heslington"
            >
            Heslington</option>
                                            <option value="Hessle"
            >
            Hessle</option>
                                            <option value="Heswall"
            >
            Heswall</option>
                                            <option value="Hethersett"
            >
            Hethersett</option>
                                            <option value="Hetton le Hole"
            >
            Hetton le Hole</option>
                                            <option value="Hexham"
            >
            Hexham</option>
                                            <option value="Heybridge"
            >
            Heybridge</option>
                                            <option value="Heysham"
            >
            Heysham</option>
                                            <option value="Heywood"
            >
            Heywood</option>
                                            <option value="High Ackworth"
            >
            High Ackworth</option>
                                            <option value="High Bentham"
            >
            High Bentham</option>
                                            <option value="High Blantyre"
            >
            High Blantyre</option>
                                            <option value="High Wycombe"
            >
            High Wycombe</option>
                                            <option value="Higham"
            >
            Higham</option>
                                            <option value="Higham Ferrers"
            >
            Higham Ferrers</option>
                                            <option value="Highbridge"
            >
            Highbridge</option>
                                            <option value="Highbury"
            >
            Highbury</option>
                                            <option value="Highcliffe"
            >
            Highcliffe</option>
                                            <option value="Highgate"
            >
            Highgate</option>
                                            <option value="Highley"
            >
            Highley</option>
                                            <option value="Highworth"
            >
            Highworth</option>
                                            <option value="Hildenborough"
            >
            Hildenborough</option>
                                            <option value="Hilperton"
            >
            Hilperton</option>
                                            <option value="Hilsea"
            >
            Hilsea</option>
                                            <option value="Hinckley"
            >
            Hinckley</option>
                                            <option value="Hindhead"
            >
            Hindhead</option>
                                            <option value="Hindley"
            >
            Hindley</option>
                                            <option value="Hipperholme"
            >
            Hipperholme</option>
                                            <option value="Hirwaun"
            >
            Hirwaun</option>
                                            <option value="Hitchin"
            >
            Hitchin</option>
                                            <option value="Hockley"
            >
            Hockley</option>
                                            <option value="Hoddesdon"
            >
            Hoddesdon</option>
                                            <option value="Holbeach"
            >
            Holbeach</option>
                                            <option value="Holborn"
            >
            Holborn</option>
                                            <option value="Hollington"
            >
            Hollington</option>
                                            <option value="Hollinwood"
            >
            Hollinwood</option>
                                            <option value="Holme upon Spalding Moor"
            >
            Holme upon Spalding Moor</option>
                                            <option value="Holmes Chapel"
            >
            Holmes Chapel</option>
                                            <option value="Holmewood"
            >
            Holmewood</option>
                                            <option value="Holmfirth"
            >
            Holmfirth</option>
                                            <option value="Holsworthy"
            >
            Holsworthy</option>
                                            <option value="Holt"
            >
            Holt</option>
                                            <option value="Holton le Clay"
            >
            Holton le Clay</option>
                                            <option value="Holyhead"
            >
            Holyhead</option>
                                            <option value="Holytown"
            >
            Holytown</option>
                                            <option value="Holywell"
            >
            Holywell</option>
                                            <option value="Holywell"
            >
            Holywell</option>
                                            <option value="Holywood"
            >
            Holywood</option>
                                            <option value="Honiton"
            >
            Honiton</option>
                                            <option value="Honley"
            >
            Honley</option>
                                            <option value="Hook"
            >
            Hook</option>
                                            <option value="Hope"
            >
            Hope</option>
                                            <option value="Hopton on Sea"
            >
            Hopton on Sea</option>
                                            <option value="Horam"
            >
            Horam</option>
                                            <option value="Horbury"
            >
            Horbury</option>
                                            <option value="Horden"
            >
            Horden</option>
                                            <option value="Hordle"
            >
            Hordle</option>
                                            <option value="Horley"
            >
            Horley</option>
                                            <option value="Horncastle"
            >
            Horncastle</option>
                                            <option value="Hornchurch"
            >
            Hornchurch</option>
                                            <option value="Horndean"
            >
            Horndean</option>
                                            <option value="Hornsea"
            >
            Hornsea</option>
                                            <option value="Hornsey"
            >
            Hornsey</option>
                                            <option value="Horsehay"
            >
            Horsehay</option>
                                            <option value="Horsell"
            >
            Horsell</option>
                                            <option value="Horsford"
            >
            Horsford</option>
                                            <option value="Horsforth"
            >
            Horsforth</option>
                                            <option value="Horsham"
            >
            Horsham</option>
                                            <option value="Horwich"
            >
            Horwich</option>
                                            <option value="Houghton le Spring"
            >
            Houghton le Spring</option>
                                            <option value="Houghton Regis"
            >
            Houghton Regis</option>
                                            <option value="Hounslow"
            >
            Hounslow</option>
                                            <option value="Houston"
            >
            Houston</option>
                                            <option value="Hove"
            >
            Hove</option>
                                            <option value="Howden"
            >
            Howden</option>
                                            <option value="Hoylake"
            >
            Hoylake</option>
                                            <option value="Hoyland Nether"
            >
            Hoyland Nether</option>
                                            <option value="Hucclecote"
            >
            Hucclecote</option>
                                            <option value="Hucknall under Huthwaite"
            >
            Hucknall under Huthwaite</option>
                                            <option value="Huddersfield"
            >
            Huddersfield</option>
                                            <option value="Hugglescote"
            >
            Hugglescote</option>
                                            <option value="Hughenden"
            >
            Hughenden</option>
                                            <option value="Humberston"
            >
            Humberston</option>
                                            <option value="Huncoat"
            >
            Huncoat</option>
                                            <option value="Hungerford"
            >
            Hungerford</option>
                                            <option value="Hunmanby"
            >
            Hunmanby</option>
                                            <option value="Hunstanton"
            >
            Hunstanton</option>
                                            <option value="Hunters Quay"
            >
            Hunters Quay</option>
                                            <option value="Huntingdon"
            >
            Huntingdon</option>
                                            <option value="Huntington"
            >
            Huntington</option>
                                            <option value="Huntington"
            >
            Huntington</option>
                                            <option value="Huntly"
            >
            Huntly</option>
                                            <option value="Hurlford"
            >
            Hurlford</option>
                                            <option value="Hutton"
            >
            Hutton</option>
                                            <option value="Huyton"
            >
            Huyton</option>
                                            <option value="Hwlffordd"
            >
            Hwlffordd</option>
                                            <option value="Hyde"
            >
            Hyde</option>
                                            <option value="Hythe"
            >
            Hythe</option>
                                            <option value="Hythe"
            >
            Hythe</option>
                                            <option value="Ibstock"
            >
            Ibstock</option>
                                            <option value="Ickenham"
            >
            Ickenham</option>
                                            <option value="Icklesham"
            >
            Icklesham</option>
                                            <option value="Ifield"
            >
            Ifield</option>
                                            <option value="Ilford"
            >
            Ilford</option>
                                            <option value="Ilfracombe"
            >
            Ilfracombe</option>
                                            <option value="Ilkeston"
            >
            Ilkeston</option>
                                            <option value="Ilkley"
            >
            Ilkley</option>
                                            <option value="Illogan"
            >
            Illogan</option>
                                            <option value="Ilminster"
            >
            Ilminster</option>
                                            <option value="Immingham"
            >
            Immingham</option>
                                            <option value="Ince-in-Makerfield"
            >
            Ince-in-Makerfield</option>
                                            <option value="Ingatestone"
            >
            Ingatestone</option>
                                            <option value="Innerleithen"
            >
            Innerleithen</option>
                                            <option value="Invergordon"
            >
            Invergordon</option>
                                            <option value="Inverkeithing"
            >
            Inverkeithing</option>
                                            <option value="Inverkip"
            >
            Inverkip</option>
                                            <option value="Inverness"
            >
            Inverness</option>
                                            <option value="Inverurie"
            >
            Inverurie</option>
                                            <option value="Ipswich"
            >
            Ipswich</option>
                                            <option value="Irchester"
            >
            Irchester</option>
                                            <option value="Irlam"
            >
            Irlam</option>
                                            <option value="Ironbridge"
            >
            Ironbridge</option>
                                            <option value="Irthlingborough"
            >
            Irthlingborough</option>
                                            <option value="Irvine"
            >
            Irvine</option>
                                            <option value="Islington"
            >
            Islington</option>
                                            <option value="Iver"
            >
            Iver</option>
                                            <option value="Ivybridge"
            >
            Ivybridge</option>
                                            <option value="Iwade"
            >
            Iwade</option>
                                            <option value="Jarrow"
            >
            Jarrow</option>
                                            <option value="Jedburgh"
            >
            Jedburgh</option>
                                            <option value="Johnstone"
            >
            Johnstone</option>
                                            <option value="Kearsley"
            >
            Kearsley</option>
                                            <option value="Keele"
            >
            Keele</option>
                                            <option value="Kegworth"
            >
            Kegworth</option>
                                            <option value="Keighley"
            >
            Keighley</option>
                                            <option value="Keith"
            >
            Keith</option>
                                            <option value="Kelsall"
            >
            Kelsall</option>
                                            <option value="Kelso"
            >
            Kelso</option>
                                            <option value="Kelty"
            >
            Kelty</option>
                                            <option value="Kelvedon"
            >
            Kelvedon</option>
                                            <option value="Kelvedon Hatch"
            >
            Kelvedon Hatch</option>
                                            <option value="Kemnay"
            >
            Kemnay</option>
                                            <option value="Kempsey"
            >
            Kempsey</option>
                                            <option value="Kempston"
            >
            Kempston</option>
                                            <option value="Kemsing"
            >
            Kemsing</option>
                                            <option value="Kendal"
            >
            Kendal</option>
                                            <option value="Kenilworth"
            >
            Kenilworth</option>
                                            <option value="Kennoway"
            >
            Kennoway</option>
                                            <option value="Kensington"
            >
            Kensington</option>
                                            <option value="Kenton"
            >
            Kenton</option>
                                            <option value="Kenton"
            >
            Kenton</option>
                                            <option value="Kessingland"
            >
            Kessingland</option>
                                            <option value="Keswick"
            >
            Keswick</option>
                                            <option value="Kettering"
            >
            Kettering</option>
                                            <option value="Kew Green"
            >
            Kew Green</option>
                                            <option value="Keynsham"
            >
            Keynsham</option>
                                            <option value="Kidbrooke"
            >
            Kidbrooke</option>
                                            <option value="Kidderminster"
            >
            Kidderminster</option>
                                            <option value="Kidlington"
            >
            Kidlington</option>
                                            <option value="Kidsgrove"
            >
            Kidsgrove</option>
                                            <option value="Kidwelly"
            >
            Kidwelly</option>
                                            <option value="Kilbarchan"
            >
            Kilbarchan</option>
                                            <option value="Kilbirnie"
            >
            Kilbirnie</option>
                                            <option value="Kilburn"
            >
            Kilburn</option>
                                            <option value="Killamarsh"
            >
            Killamarsh</option>
                                            <option value="Killingworth"
            >
            Killingworth</option>
                                            <option value="Kilmacolm"
            >
            Kilmacolm</option>
                                            <option value="Kilmarnock"
            >
            Kilmarnock</option>
                                            <option value="Kilmaurs"
            >
            Kilmaurs</option>
                                            <option value="Kilnhurst"
            >
            Kilnhurst</option>
                                            <option value="Kilsyth"
            >
            Kilsyth</option>
                                            <option value="Kilwinning"
            >
            Kilwinning</option>
                                            <option value="Kimberley"
            >
            Kimberley</option>
                                            <option value="Kincardine"
            >
            Kincardine</option>
                                            <option value="Kinghorn"
            >
            Kinghorn</option>
                                            <option value="Kings Langley"
            >
            Kings Langley</option>
                                            <option value="Kings Norton"
            >
            Kings Norton</option>
                                            <option value="Kings Worthy"
            >
            Kings Worthy</option>
                                            <option value="Kingsbridge"
            >
            Kingsbridge</option>
                                            <option value="Kingsbury"
            >
            Kingsbury</option>
                                            <option value="Kingsclere"
            >
            Kingsclere</option>
                                            <option value="Kingsnorth"
            >
            Kingsnorth</option>
                                            <option value="Kingsteignton"
            >
            Kingsteignton</option>
                                            <option value="Kingsthorpe"
            >
            Kingsthorpe</option>
                                            <option value="Kingston upon Hull"
            >
            Kingston upon Hull</option>
                                            <option value="Kingston upon Thames"
            >
            Kingston upon Thames</option>
                                            <option value="Kingswinford"
            >
            Kingswinford</option>
                                            <option value="Kingswood"
            >
            Kingswood</option>
                                            <option value="Kingswood"
            >
            Kingswood</option>
                                            <option value="Kington"
            >
            Kington</option>
                                            <option value="King’s Lynn"
            >
            King’s Lynn</option>
                                            <option value="Kinmel"
            >
            Kinmel</option>
                                            <option value="Kinross"
            >
            Kinross</option>
                                            <option value="Kintbury"
            >
            Kintbury</option>
                                            <option value="Kintore"
            >
            Kintore</option>
                                            <option value="Kinvere"
            >
            Kinvere</option>
                                            <option value="Kippax"
            >
            Kippax</option>
                                            <option value="Kirby Muxloe"
            >
            Kirby Muxloe</option>
                                            <option value="Kirk of Shotts"
            >
            Kirk of Shotts</option>
                                            <option value="Kirkburton"
            >
            Kirkburton</option>
                                            <option value="Kirkby"
            >
            Kirkby</option>
                                            <option value="Kirkby in Ashfield"
            >
            Kirkby in Ashfield</option>
                                            <option value="Kirkby Moorside"
            >
            Kirkby Moorside</option>
                                            <option value="Kirkcaldy"
            >
            Kirkcaldy</option>
                                            <option value="Kirkcudbright"
            >
            Kirkcudbright</option>
                                            <option value="Kirkham"
            >
            Kirkham</option>
                                            <option value="Kirkintilloch"
            >
            Kirkintilloch</option>
                                            <option value="Kirkleatham"
            >
            Kirkleatham</option>
                                            <option value="Kirkliston"
            >
            Kirkliston</option>
                                            <option value="Kirkstall"
            >
            Kirkstall</option>
                                            <option value="Kirkwall"
            >
            Kirkwall</option>
                                            <option value="Kirriemuir"
            >
            Kirriemuir</option>
                                            <option value="Kirton"
            >
            Kirton</option>
                                            <option value="Kirton in Lindsey"
            >
            Kirton in Lindsey</option>
                                            <option value="Knaresborough"
            >
            Knaresborough</option>
                                            <option value="Knebworth"
            >
            Knebworth</option>
                                            <option value="Knighton"
            >
            Knighton</option>
                                            <option value="Knottingley"
            >
            Knottingley</option>
                                            <option value="Knowle"
            >
            Knowle</option>
                                            <option value="Knowsley"
            >
            Knowsley</option>
                                            <option value="Knutsford"
            >
            Knutsford</option>
                                            <option value="Laceby"
            >
            Laceby</option>
                                            <option value="Lakenheath"
            >
            Lakenheath</option>
                                            <option value="Laleham"
            >
            Laleham</option>
                                            <option value="Lambeth"
            >
            Lambeth</option>
                                            <option value="Lambourn"
            >
            Lambourn</option>
                                            <option value="Lamesley"
            >
            Lamesley</option>
                                            <option value="Lampeter"
            >
            Lampeter</option>
                                            <option value="Lanark"
            >
            Lanark</option>
                                            <option value="Lancaster"
            >
            Lancaster</option>
                                            <option value="Lancing"
            >
            Lancing</option>
                                            <option value="Landore"
            >
            Landore</option>
                                            <option value="Langford"
            >
            Langford</option>
                                            <option value="Larbert"
            >
            Larbert</option>
                                            <option value="Largs"
            >
            Largs</option>
                                            <option value="Larkhall"
            >
            Larkhall</option>
                                            <option value="Latchford"
            >
            Latchford</option>
                                            <option value="Launceston"
            >
            Launceston</option>
                                            <option value="Laurencekirk"
            >
            Laurencekirk</option>
                                            <option value="Law"
            >
            Law</option>
                                            <option value="Lawford"
            >
            Lawford</option>
                                            <option value="Lea Town"
            >
            Lea Town</option>
                                            <option value="Leagrave"
            >
            Leagrave</option>
                                            <option value="Leatherhead"
            >
            Leatherhead</option>
                                            <option value="Leavesden Green"
            >
            Leavesden Green</option>
                                            <option value="Lechlade"
            >
            Lechlade</option>
                                            <option value="Ledbury"
            >
            Ledbury</option>
                                            <option value="Leeds"
            >
            Leeds</option>
                                            <option value="Leek"
            >
            Leek</option>
                                            <option value="Lees"
            >
            Lees</option>
                                            <option value="Leicester"
            >
            Leicester</option>
                                            <option value="Leigh"
            >
            Leigh</option>
                                            <option value="Leigh-on-Sea"
            >
            Leigh-on-Sea</option>
                                            <option value="Leighton Buzzard"
            >
            Leighton Buzzard</option>
                                            <option value="Leiston"
            >
            Leiston</option>
                                            <option value="Leith"
            >
            Leith</option>
                                            <option value="Lemington"
            >
            Lemington</option>
                                            <option value="Lenham"
            >
            Lenham</option>
                                            <option value="Lennoxtown"
            >
            Lennoxtown</option>
                                            <option value="Leominster"
            >
            Leominster</option>
                                            <option value="Lerwick"
            >
            Lerwick</option>
                                            <option value="Leslie"
            >
            Leslie</option>
                                            <option value="Lesmahagow"
            >
            Lesmahagow</option>
                                            <option value="Letchworth"
            >
            Letchworth</option>
                                            <option value="Leven"
            >
            Leven</option>
                                            <option value="Leverington"
            >
            Leverington</option>
                                            <option value="Lewes"
            >
            Lewes</option>
                                            <option value="Lexden"
            >
            Lexden</option>
                                            <option value="Leyland"
            >
            Leyland</option>
                                            <option value="Leyton"
            >
            Leyton</option>
                                            <option value="Lichfield"
            >
            Lichfield</option>
                                            <option value="Lickey End"
            >
            Lickey End</option>
                                            <option value="Limpsfield"
            >
            Limpsfield</option>
                                            <option value="Lincoln"
            >
            Lincoln</option>
                                            <option value="Lindfield"
            >
            Lindfield</option>
                                            <option value="Lingfield"
            >
            Lingfield</option>
                                            <option value="Lingwood"
            >
            Lingwood</option>
                                            <option value="Linlithgow"
            >
            Linlithgow</option>
                                            <option value="Linslade"
            >
            Linslade</option>
                                            <option value="Linthwaite"
            >
            Linthwaite</option>
                                            <option value="Linton"
            >
            Linton</option>
                                            <option value="Liphook"
            >
            Liphook</option>
                                            <option value="Lisburn"
            >
            Lisburn</option>
                                            <option value="Liss"
            >
            Liss</option>
                                            <option value="Litherland"
            >
            Litherland</option>
                                            <option value="Little Hulton"
            >
            Little Hulton</option>
                                            <option value="Little Lever"
            >
            Little Lever</option>
                                            <option value="Little Paxton"
            >
            Little Paxton</option>
                                            <option value="Little Plumstead"
            >
            Little Plumstead</option>
                                            <option value="Littleborough"
            >
            Littleborough</option>
                                            <option value="Littlehampton"
            >
            Littlehampton</option>
                                            <option value="Littlemore"
            >
            Littlemore</option>
                                            <option value="Littleover"
            >
            Littleover</option>
                                            <option value="Littleport"
            >
            Littleport</option>
                                            <option value="Liverpool"
            >
            Liverpool</option>
                                            <option value="Liversedge"
            >
            Liversedge</option>
                                            <option value="Livingston"
            >
            Livingston</option>
                                            <option value="Llanasa"
            >
            Llanasa</option>
                                            <option value="Llanbadarn-fawr"
            >
            Llanbadarn-fawr</option>
                                            <option value="Llanbradach"
            >
            Llanbradach</option>
                                            <option value="Llandow"
            >
            Llandow</option>
                                            <option value="Llandudno"
            >
            Llandudno</option>
                                            <option value="Llandudno Junction"
            >
            Llandudno Junction</option>
                                            <option value="Llandwrog"
            >
            Llandwrog</option>
                                            <option value="Llanelli"
            >
            Llanelli</option>
                                            <option value="Llanfairfechan"
            >
            Llanfairfechan</option>
                                            <option value="Llanfairpwllgwyngyll"
            >
            Llanfairpwllgwyngyll</option>
                                            <option value="Llangefni"
            >
            Llangefni</option>
                                            <option value="Llangendeirne"
            >
            Llangendeirne</option>
                                            <option value="Llangennech"
            >
            Llangennech</option>
                                            <option value="Llangollen"
            >
            Llangollen</option>
                                            <option value="Llangynwyd"
            >
            Llangynwyd</option>
                                            <option value="Llanharan"
            >
            Llanharan</option>
                                            <option value="Llanharry"
            >
            Llanharry</option>
                                            <option value="Llanidloes"
            >
            Llanidloes</option>
                                            <option value="Llanllyfni"
            >
            Llanllyfni</option>
                                            <option value="Llanrug"
            >
            Llanrug</option>
                                            <option value="Llanrwst"
            >
            Llanrwst</option>
                                            <option value="Llantarnam"
            >
            Llantarnam</option>
                                            <option value="Llantrisant"
            >
            Llantrisant</option>
                                            <option value="Llantwit Fardre"
            >
            Llantwit Fardre</option>
                                            <option value="Llantwit Major"
            >
            Llantwit Major</option>
                                            <option value="Llanwern"
            >
            Llanwern</option>
                                            <option value="Llysfaen"
            >
            Llysfaen</option>
                                            <option value="Loanhead"
            >
            Loanhead</option>
                                            <option value="Locharbriggs"
            >
            Locharbriggs</option>
                                            <option value="Lochgelly"
            >
            Lochgelly</option>
                                            <option value="Lochwinnoch"
            >
            Lochwinnoch</option>
                                            <option value="Lockerbie"
            >
            Lockerbie</option>
                                            <option value="Locking"
            >
            Locking</option>
                                            <option value="Loddon"
            >
            Loddon</option>
                                            <option value="Loftus"
            >
            Loftus</option>
                                            <option value="London"
            >
            London</option>
                                            <option value="London Colney"
            >
            London Colney</option>
                                            <option value="Long Ashton"
            >
            Long Ashton</option>
                                            <option value="Long Buckby"
            >
            Long Buckby</option>
                                            <option value="Long Ditton"
            >
            Long Ditton</option>
                                            <option value="Long Eaton"
            >
            Long Eaton</option>
                                            <option value="Long Lawford"
            >
            Long Lawford</option>
                                            <option value="Long Melford"
            >
            Long Melford</option>
                                            <option value="Long Stanton"
            >
            Long Stanton</option>
                                            <option value="Long Stratton"
            >
            Long Stratton</option>
                                            <option value="Long Sutton"
            >
            Long Sutton</option>
                                            <option value="Longbenton"
            >
            Longbenton</option>
                                            <option value="Longbridge"
            >
            Longbridge</option>
                                            <option value="Longfield"
            >
            Longfield</option>
                                            <option value="Longridge"
            >
            Longridge</option>
                                            <option value="Longton"
            >
            Longton</option>
                                            <option value="Longton"
            >
            Longton</option>
                                            <option value="Longtown"
            >
            Longtown</option>
                                            <option value="Looe"
            >
            Looe</option>
                                            <option value="Lossiemouth"
            >
            Lossiemouth</option>
                                            <option value="Lostwithiel"
            >
            Lostwithiel</option>
                                            <option value="Loudwater"
            >
            Loudwater</option>
                                            <option value="Loughborough"
            >
            Loughborough</option>
                                            <option value="Loughor"
            >
            Loughor</option>
                                            <option value="Loughton"
            >
            Loughton</option>
                                            <option value="Louth"
            >
            Louth</option>
                                            <option value="Lowdham"
            >
            Lowdham</option>
                                            <option value="Lower Kingswood"
            >
            Lower Kingswood</option>
                                            <option value="Lowestoft"
            >
            Lowestoft</option>
                                            <option value="Lowton"
            >
            Lowton</option>
                                            <option value="Luddenden Foot"
            >
            Luddenden Foot</option>
                                            <option value="Ludgershall"
            >
            Ludgershall</option>
                                            <option value="Ludlow"
            >
            Ludlow</option>
                                            <option value="Luton"
            >
            Luton</option>
                                            <option value="Lydd"
            >
            Lydd</option>
                                            <option value="Lydiate"
            >
            Lydiate</option>
                                            <option value="Lydney"
            >
            Lydney</option>
                                            <option value="Lye"
            >
            Lye</option>
                                            <option value="Lyme Regis"
            >
            Lyme Regis</option>
                                            <option value="Lyminge"
            >
            Lyminge</option>
                                            <option value="Lymington"
            >
            Lymington</option>
                                            <option value="Lymm"
            >
            Lymm</option>
                                            <option value="Lyndhurst"
            >
            Lyndhurst</option>
                                            <option value="Lyneham"
            >
            Lyneham</option>
                                            <option value="Lytchett Matravers"
            >
            Lytchett Matravers</option>
                                            <option value="Mablethorpe"
            >
            Mablethorpe</option>
                                            <option value="Macclesfield"
            >
            Macclesfield</option>
                                            <option value="Macduff"
            >
            Macduff</option>
                                            <option value="Mackworth"
            >
            Mackworth</option>
                                            <option value="Madeley"
            >
            Madeley</option>
                                            <option value="Madeley"
            >
            Madeley</option>
                                            <option value="Maerdy"
            >
            Maerdy</option>
                                            <option value="Maesteg"
            >
            Maesteg</option>
                                            <option value="Maghull"
            >
            Maghull</option>
                                            <option value="Magor"
            >
            Magor</option>
                                            <option value="Maidenhead"
            >
            Maidenhead</option>
                                            <option value="Maidstone"
            >
            Maidstone</option>
                                            <option value="Maldon"
            >
            Maldon</option>
                                            <option value="Malmesbury"
            >
            Malmesbury</option>
                                            <option value="Maltby"
            >
            Maltby</option>
                                            <option value="Malton"
            >
            Malton</option>
                                            <option value="Malvern Link"
            >
            Malvern Link</option>
                                            <option value="Malvern Wells"
            >
            Malvern Wells</option>
                                            <option value="Manchester"
            >
            Manchester</option>
                                            <option value="Mansfield"
            >
            Mansfield</option>
                                            <option value="March"
            >
            March</option>
                                            <option value="Marchwood"
            >
            Marchwood</option>
                                            <option value="Marden"
            >
            Marden</option>
                                            <option value="Maresfield"
            >
            Maresfield</option>
                                            <option value="Margam"
            >
            Margam</option>
                                            <option value="Margate"
            >
            Margate</option>
                                            <option value="Marham"
            >
            Marham</option>
                                            <option value="Market Deeping"
            >
            Market Deeping</option>
                                            <option value="Market Drayton"
            >
            Market Drayton</option>
                                            <option value="Market Harborough"
            >
            Market Harborough</option>
                                            <option value="Market Rasen"
            >
            Market Rasen</option>
                                            <option value="Market Weighton"
            >
            Market Weighton</option>
                                            <option value="Markfield"
            >
            Markfield</option>
                                            <option value="Marks Tey"
            >
            Marks Tey</option>
                                            <option value="Markyate"
            >
            Markyate</option>
                                            <option value="Marlborough"
            >
            Marlborough</option>
                                            <option value="Marlow"
            >
            Marlow</option>
                                            <option value="Marple"
            >
            Marple</option>
                                            <option value="Marsden"
            >
            Marsden</option>
                                            <option value="Marshfield"
            >
            Marshfield</option>
                                            <option value="Marston"
            >
            Marston</option>
                                            <option value="Marston Green"
            >
            Marston Green</option>
                                            <option value="Marston Moretaine"
            >
            Marston Moretaine</option>
                                            <option value="Martham"
            >
            Martham</option>
                                            <option value="Martlesham"
            >
            Martlesham</option>
                                            <option value="Martock"
            >
            Martock</option>
                                            <option value="Marton"
            >
            Marton</option>
                                            <option value="Maryport"
            >
            Maryport</option>
                                            <option value="Matlock"
            >
            Matlock</option>
                                            <option value="Mattishall"
            >
            Mattishall</option>
                                            <option value="Mauchline"
            >
            Mauchline</option>
                                            <option value="Maulden"
            >
            Maulden</option>
                                            <option value="Maybole"
            >
            Maybole</option>
                                            <option value="Mayland"
            >
            Mayland</option>
                                            <option value="Measham"
            >
            Measham</option>
                                            <option value="Melbourn"
            >
            Melbourn</option>
                                            <option value="Melbourne"
            >
            Melbourne</option>
                                            <option value="Melksham"
            >
            Melksham</option>
                                            <option value="Melling"
            >
            Melling</option>
                                            <option value="Meltham"
            >
            Meltham</option>
                                            <option value="Melton"
            >
            Melton</option>
                                            <option value="Melton Mowbray"
            >
            Melton Mowbray</option>
                                            <option value="Menai Bridge"
            >
            Menai Bridge</option>
                                            <option value="Menston"
            >
            Menston</option>
                                            <option value="Meopham"
            >
            Meopham</option>
                                            <option value="Mere"
            >
            Mere</option>
                                            <option value="Meriden"
            >
            Meriden</option>
                                            <option value="Merrow"
            >
            Merrow</option>
                                            <option value="Merstham"
            >
            Merstham</option>
                                            <option value="Merthyr Tudful"
            >
            Merthyr Tudful</option>
                                            <option value="Messingham"
            >
            Messingham</option>
                                            <option value="Metheringham"
            >
            Metheringham</option>
                                            <option value="Methil"
            >
            Methil</option>
                                            <option value="Mexborough"
            >
            Mexborough</option>
                                            <option value="Mickleover"
            >
            Mickleover</option>
                                            <option value="Mid Calder"
            >
            Mid Calder</option>
                                            <option value="Middlesbrough"
            >
            Middlesbrough</option>
                                            <option value="Middleton"
            >
            Middleton</option>
                                            <option value="Middleton-on-Sea"
            >
            Middleton-on-Sea</option>
                                            <option value="Middlewich"
            >
            Middlewich</option>
                                            <option value="Midhurst"
            >
            Midhurst</option>
                                            <option value="Midsomer Norton"
            >
            Midsomer Norton</option>
                                            <option value="Milborne Port"
            >
            Milborne Port</option>
                                            <option value="Mildenhall"
            >
            Mildenhall</option>
                                            <option value="Milford"
            >
            Milford</option>
                                            <option value="Milford Haven"
            >
            Milford Haven</option>
                                            <option value="Milford on Sea"
            >
            Milford on Sea</option>
                                            <option value="Mill Hill"
            >
            Mill Hill</option>
                                            <option value="Millom"
            >
            Millom</option>
                                            <option value="Milltimber"
            >
            Milltimber</option>
                                            <option value="Milngavie"
            >
            Milngavie</option>
                                            <option value="Milnrow"
            >
            Milnrow</option>
                                            <option value="Milton"
            >
            Milton</option>
                                            <option value="Milton"
            >
            Milton</option>
                                            <option value="Milton Keynes"
            >
            Milton Keynes</option>
                                            <option value="Milton Regis"
            >
            Milton Regis</option>
                                            <option value="Minchinhampton"
            >
            Minchinhampton</option>
                                            <option value="Minehead"
            >
            Minehead</option>
                                            <option value="Minster"
            >
            Minster</option>
                                            <option value="Mintlaw"
            >
            Mintlaw</option>
                                            <option value="Mirfield"
            >
            Mirfield</option>
                                            <option value="Mistley"
            >
            Mistley</option>
                                            <option value="Mitcham"
            >
            Mitcham</option>
                                            <option value="Mitcheldean"
            >
            Mitcheldean</option>
                                            <option value="Mobberley"
            >
            Mobberley</option>
                                            <option value="Moffat"
            >
            Moffat</option>
                                            <option value="Mold"
            >
            Mold</option>
                                            <option value="Molesey"
            >
            Molesey</option>
                                            <option value="Monifieth"
            >
            Monifieth</option>
                                            <option value="Monkseaton"
            >
            Monkseaton</option>
                                            <option value="Monmouth"
            >
            Monmouth</option>
                                            <option value="Montrose"
            >
            Montrose</option>
                                            <option value="Morden"
            >
            Morden</option>
                                            <option value="Morecambe"
            >
            Morecambe</option>
                                            <option value="Moreton"
            >
            Moreton</option>
                                            <option value="Moreton in Marsh"
            >
            Moreton in Marsh</option>
                                            <option value="Morley"
            >
            Morley</option>
                                            <option value="Morpeth"
            >
            Morpeth</option>
                                            <option value="Morriston"
            >
            Morriston</option>
                                            <option value="Mosbrough"
            >
            Mosbrough</option>
                                            <option value="Mossley"
            >
            Mossley</option>
                                            <option value="Moston"
            >
            Moston</option>
                                            <option value="Motherwell"
            >
            Motherwell</option>
                                            <option value="Moulton"
            >
            Moulton</option>
                                            <option value="Moulton"
            >
            Moulton</option>
                                            <option value="Moulton Chapel"
            >
            Moulton Chapel</option>
                                            <option value="Mountain Ash"
            >
            Mountain Ash</option>
                                            <option value="Mountsorrel"
            >
            Mountsorrel</option>
                                            <option value="Much Hadham"
            >
            Much Hadham</option>
                                            <option value="Much Wenlock"
            >
            Much Wenlock</option>
                                            <option value="Muir of Ord"
            >
            Muir of Ord</option>
                                            <option value="Mulbarton"
            >
            Mulbarton</option>
                                            <option value="Mundesley"
            >
            Mundesley</option>
                                            <option value="Murston"
            >
            Murston</option>
                                            <option value="Musselburgh"
            >
            Musselburgh</option>
                                            <option value="Muxton"
            >
            Muxton</option>
                                            <option value="Mytchett"
            >
            Mytchett</option>
                                            <option value="Mytholmroyd"
            >
            Mytholmroyd</option>
                                            <option value="Nailsea"
            >
            Nailsea</option>
                                            <option value="Nailsworth"
            >
            Nailsworth</option>
                                            <option value="Nairn"
            >
            Nairn</option>
                                            <option value="Nantwich"
            >
            Nantwich</option>
                                            <option value="Nantyglo"
            >
            Nantyglo</option>
                                            <option value="Narborough"
            >
            Narborough</option>
                                            <option value="Nazeing"
            >
            Nazeing</option>
                                            <option value="Neath"
            >
            Neath</option>
                                            <option value="Nefyn"
            >
            Nefyn</option>
                                            <option value="Neilston"
            >
            Neilston</option>
                                            <option value="Nelson"
            >
            Nelson</option>
                                            <option value="Nelson"
            >
            Nelson</option>
                                            <option value="Neston"
            >
            Neston</option>
                                            <option value="Netherton"
            >
            Netherton</option>
                                            <option value="Nettleham"
            >
            Nettleham</option>
                                            <option value="New Alresford"
            >
            New Alresford</option>
                                            <option value="New Brighton"
            >
            New Brighton</option>
                                            <option value="New Buildings"
            >
            New Buildings</option>
                                            <option value="New Cumnock"
            >
            New Cumnock</option>
                                            <option value="New Earswick"
            >
            New Earswick</option>
                                            <option value="New Inn"
            >
            New Inn</option>
                                            <option value="New Mills"
            >
            New Mills</option>
                                            <option value="New Milton"
            >
            New Milton</option>
                                            <option value="New Romney"
            >
            New Romney</option>
                                            <option value="New Scone"
            >
            New Scone</option>
                                            <option value="New Tredegar"
            >
            New Tredegar</option>
                                            <option value="Newarthill"
            >
            Newarthill</option>
                                            <option value="Newbiggin-by-the-Sea"
            >
            Newbiggin-by-the-Sea</option>
                                            <option value="Newbold"
            >
            Newbold</option>
                                            <option value="Newbold Verdon"
            >
            Newbold Verdon</option>
                                            <option value="Newbridge"
            >
            Newbridge</option>
                                            <option value="Newburn"
            >
            Newburn</option>
                                            <option value="Newbury"
            >
            Newbury</option>
                                            <option value="Newcastle"
            >
            Newcastle</option>
                                            <option value="Newcastle under Lyme"
            >
            Newcastle under Lyme</option>
                                            <option value="Newent"
            >
            Newent</option>
                                            <option value="Newhaven"
            >
            Newhaven</option>
                                            <option value="Newhouse"
            >
            Newhouse</option>
                                            <option value="Newington"
            >
            Newington</option>
                                            <option value="Newlyn"
            >
            Newlyn</option>
                                            <option value="Newmacher"
            >
            Newmacher</option>
                                            <option value="Newmains"
            >
            Newmains</option>
                                            <option value="Newmarket"
            >
            Newmarket</option>
                                            <option value="Newmilns"
            >
            Newmilns</option>
                                            <option value="Newport"
            >
            Newport</option>
                                            <option value="Newport"
            >
            Newport</option>
                                            <option value="Newport"
            >
            Newport</option>
                                            <option value="Newport Pagnell"
            >
            Newport Pagnell</option>
                                            <option value="Newport-On-Tay"
            >
            Newport-On-Tay</option>
                                            <option value="Newquay"
            >
            Newquay</option>
                                            <option value="Newton"
            >
            Newton</option>
                                            <option value="Newton Abbot"
            >
            Newton Abbot</option>
                                            <option value="Newton Aycliffe"
            >
            Newton Aycliffe</option>
                                            <option value="Newton Grange"
            >
            Newton Grange</option>
                                            <option value="Newton in Makerfield"
            >
            Newton in Makerfield</option>
                                            <option value="Newton Mearns"
            >
            Newton Mearns</option>
                                            <option value="Newton Stewart"
            >
            Newton Stewart</option>
                                            <option value="Newtonhill"
            >
            Newtonhill</option>
                                            <option value="Newtown"
            >
            Newtown</option>
                                            <option value="Neyland"
            >
            Neyland</option>
                                            <option value="Normandy"
            >
            Normandy</option>
                                            <option value="Normanton"
            >
            Normanton</option>
                                            <option value="North Berwick"
            >
            North Berwick</option>
                                            <option value="North Collingham"
            >
            North Collingham</option>
                                            <option value="North Elmsall"
            >
            North Elmsall</option>
                                            <option value="North Ferriby"
            >
            North Ferriby</option>
                                            <option value="North Hinksey"
            >
            North Hinksey</option>
                                            <option value="North Hykeham"
            >
            North Hykeham</option>
                                            <option value="North Petherton"
            >
            North Petherton</option>
                                            <option value="North Shields"
            >
            North Shields</option>
                                            <option value="North Tidworth"
            >
            North Tidworth</option>
                                            <option value="North Walsham"
            >
            North Walsham</option>
                                            <option value="Northallerton"
            >
            Northallerton</option>
                                            <option value="Northam"
            >
            Northam</option>
                                            <option value="Northchurch"
            >
            Northchurch</option>
                                            <option value="Northenden"
            >
            Northenden</option>
                                            <option value="Northfield"
            >
            Northfield</option>
                                            <option value="Northfleet"
            >
            Northfleet</option>
                                            <option value="Northolt"
            >
            Northolt</option>
                                            <option value="Northop"
            >
            Northop</option>
                                            <option value="Northwich"
            >
            Northwich</option>
                                            <option value="Northwood"
            >
            Northwood</option>
                                            <option value="Norton"
            >
            Norton</option>
                                            <option value="Norton Canes"
            >
            Norton Canes</option>
                                            <option value="Norton Fitzwarren"
            >
            Norton Fitzwarren</option>
                                            <option value="Norwich"
            >
            Norwich</option>
                                            <option value="Nottage"
            >
            Nottage</option>
                                            <option value="Nottingham"
            >
            Nottingham</option>
                                            <option value="Nuneaton"
            >
            Nuneaton</option>
                                            <option value="Nunthorpe"
            >
            Nunthorpe</option>
                                            <option value="Nursling"
            >
            Nursling</option>
                                            <option value="Nutfield"
            >
            Nutfield</option>
                                            <option value="Oadby"
            >
            Oadby</option>
                                            <option value="Oakengates"
            >
            Oakengates</option>
                                            <option value="Oakham"
            >
            Oakham</option>
                                            <option value="Oakley"
            >
            Oakley</option>
                                            <option value="Oban"
            >
            Oban</option>
                                            <option value="Odiham"
            >
            Odiham</option>
                                            <option value="Ogmore Vale"
            >
            Ogmore Vale</option>
                                            <option value="Okehampton"
            >
            Okehampton</option>
                                            <option value="Old Brampton"
            >
            Old Brampton</option>
                                            <option value="Old Colwyn"
            >
            Old Colwyn</option>
                                            <option value="Old Kilpatrick"
            >
            Old Kilpatrick</option>
                                            <option value="Old Windsor"
            >
            Old Windsor</option>
                                            <option value="Oldbury"
            >
            Oldbury</option>
                                            <option value="Oldham"
            >
            Oldham</option>
                                            <option value="Ollerton"
            >
            Ollerton</option>
                                            <option value="Olney"
            >
            Olney</option>
                                            <option value="Olton"
            >
            Olton</option>
                                            <option value="Omagh"
            >
            Omagh</option>
                                            <option value="Ore"
            >
            Ore</option>
                                            <option value="Ormesby"
            >
            Ormesby</option>
                                            <option value="Ormesby Saint Margaret"
            >
            Ormesby Saint Margaret</option>
                                            <option value="Ormskirk"
            >
            Ormskirk</option>
                                            <option value="Orsett"
            >
            Orsett</option>
                                            <option value="Ossett"
            >
            Ossett</option>
                                            <option value="Oswaldtwistle"
            >
            Oswaldtwistle</option>
                                            <option value="Oswestry"
            >
            Oswestry</option>
                                            <option value="Otford"
            >
            Otford</option>
                                            <option value="Otley"
            >
            Otley</option>
                                            <option value="Ottershaw"
            >
            Ottershaw</option>
                                            <option value="Ottery Saint Mary"
            >
            Ottery Saint Mary</option>
                                            <option value="Oughtibridge"
            >
            Oughtibridge</option>
                                            <option value="Oundle"
            >
            Oundle</option>
                                            <option value="Ovenden"
            >
            Ovenden</option>
                                            <option value="Over"
            >
            Over</option>
                                            <option value="Overton"
            >
            Overton</option>
                                            <option value="Oxenhope"
            >
            Oxenhope</option>
                                            <option value="Oxford"
            >
            Oxford</option>
                                            <option value="Oxshott"
            >
            Oxshott</option>
                                            <option value="Oxted"
            >
            Oxted</option>
                                            <option value="Oystermouth"
            >
            Oystermouth</option>
                                            <option value="Paddock Wood"
            >
            Paddock Wood</option>
                                            <option value="Padiham"
            >
            Padiham</option>
                                            <option value="Padstow"
            >
            Padstow</option>
                                            <option value="Pagham"
            >
            Pagham</option>
                                            <option value="Paignton"
            >
            Paignton</option>
                                            <option value="Painswick"
            >
            Painswick</option>
                                            <option value="Paisley"
            >
            Paisley</option>
                                            <option value="Pakefield"
            >
            Pakefield</option>
                                            <option value="Palmers Green"
            >
            Palmers Green</option>
                                            <option value="Pangbourne"
            >
            Pangbourne</option>
                                            <option value="Pannal"
            >
            Pannal</option>
                                            <option value="Papworth Everard"
            >
            Papworth Everard</option>
                                            <option value="Parbold"
            >
            Parbold</option>
                                            <option value="Park Street"
            >
            Park Street</option>
                                            <option value="Parkgate"
            >
            Parkgate</option>
                                            <option value="Parkstone"
            >
            Parkstone</option>
                                            <option value="Partick"
            >
            Partick</option>
                                            <option value="Partington"
            >
            Partington</option>
                                            <option value="Patcham"
            >
            Patcham</option>
                                            <option value="Paulton"
            >
            Paulton</option>
                                            <option value="Peasedown Saint John"
            >
            Peasedown Saint John</option>
                                            <option value="Peebles"
            >
            Peebles</option>
                                            <option value="Pegswood"
            >
            Pegswood</option>
                                            <option value="Pelsall"
            >
            Pelsall</option>
                                            <option value="Pemberton"
            >
            Pemberton</option>
                                            <option value="Pembroke"
            >
            Pembroke</option>
                                            <option value="Pembroke Dock"
            >
            Pembroke Dock</option>
                                            <option value="Pembury"
            >
            Pembury</option>
                                            <option value="Pen-y-Bont ar Ogwr"
            >
            Pen-y-Bont ar Ogwr</option>
                                            <option value="Pen-y-cae"
            >
            Pen-y-cae</option>
                                            <option value="Penarth"
            >
            Penarth</option>
                                            <option value="Penclawdd"
            >
            Penclawdd</option>
                                            <option value="Pencoed"
            >
            Pencoed</option>
                                            <option value="Pendlebury"
            >
            Pendlebury</option>
                                            <option value="Pengam"
            >
            Pengam</option>
                                            <option value="Penicuik"
            >
            Penicuik</option>
                                            <option value="Penistone"
            >
            Penistone</option>
                                            <option value="Penkridge"
            >
            Penkridge</option>
                                            <option value="Penmaenmawr"
            >
            Penmaenmawr</option>
                                            <option value="Penn"
            >
            Penn</option>
                                            <option value="Penrith"
            >
            Penrith</option>
                                            <option value="Penryn"
            >
            Penryn</option>
                                            <option value="Pentyrch"
            >
            Pentyrch</option>
                                            <option value="Penwortham"
            >
            Penwortham</option>
                                            <option value="Penzance"
            >
            Penzance</option>
                                            <option value="Perivale"
            >
            Perivale</option>
                                            <option value="Perranporth"
            >
            Perranporth</option>
                                            <option value="Perranzabuloe"
            >
            Perranzabuloe</option>
                                            <option value="Perry Barr"
            >
            Perry Barr</option>
                                            <option value="Pershore"
            >
            Pershore</option>
                                            <option value="Perth"
            >
            Perth</option>
                                            <option value="Peterculter"
            >
            Peterculter</option>
                                            <option value="Peterhead"
            >
            Peterhead</option>
                                            <option value="Peterlee"
            >
            Peterlee</option>
                                            <option value="Petersfield"
            >
            Petersfield</option>
                                            <option value="Petworth"
            >
            Petworth</option>
                                            <option value="Pevensey"
            >
            Pevensey</option>
                                            <option value="Pewsey"
            >
            Pewsey</option>
                                            <option value="Pickering"
            >
            Pickering</option>
                                            <option value="Pilning"
            >
            Pilning</option>
                                            <option value="Pilsley"
            >
            Pilsley</option>
                                            <option value="Pinchbeck"
            >
            Pinchbeck</option>
                                            <option value="Pinhoe"
            >
            Pinhoe</option>
                                            <option value="Pinner"
            >
            Pinner</option>
                                            <option value="Pinxton"
            >
            Pinxton</option>
                                            <option value="Pirbright"
            >
            Pirbright</option>
                                            <option value="Pitlochry"
            >
            Pitlochry</option>
                                            <option value="Pitsea"
            >
            Pitsea</option>
                                            <option value="Pitstone"
            >
            Pitstone</option>
                                            <option value="Plumstead"
            >
            Plumstead</option>
                                            <option value="Plymouth"
            >
            Plymouth</option>
                                            <option value="Plympton"
            >
            Plympton</option>
                                            <option value="Plymstock"
            >
            Plymstock</option>
                                            <option value="Pocklington"
            >
            Pocklington</option>
                                            <option value="Polegate"
            >
            Polegate</option>
                                            <option value="Polesworth"
            >
            Polesworth</option>
                                            <option value="Polmont"
            >
            Polmont</option>
                                            <option value="Pont-y-pŵl"
            >
            Pont-y-pŵl</option>
                                            <option value="Pontardawe"
            >
            Pontardawe</option>
                                            <option value="Pontardulais"
            >
            Pontardulais</option>
                                            <option value="Pontefract"
            >
            Pontefract</option>
                                            <option value="Ponteland"
            >
            Ponteland</option>
                                            <option value="Pontesbury"
            >
            Pontesbury</option>
                                            <option value="Pontyberem"
            >
            Pontyberem</option>
                                            <option value="Pontycymer"
            >
            Pontycymer</option>
                                            <option value="Pontypridd"
            >
            Pontypridd</option>
                                            <option value="Poole"
            >
            Poole</option>
                                            <option value="Poringland"
            >
            Poringland</option>
                                            <option value="Port Glasgow"
            >
            Port Glasgow</option>
                                            <option value="Port Talbot"
            >
            Port Talbot</option>
                                            <option value="Portchester"
            >
            Portchester</option>
                                            <option value="Porth"
            >
            Porth</option>
                                            <option value="Porthcawl"
            >
            Porthcawl</option>
                                            <option value="Porthleven"
            >
            Porthleven</option>
                                            <option value="Porthmadog"
            >
            Porthmadog</option>
                                            <option value="Portishead"
            >
            Portishead</option>
                                            <option value="Portlethen"
            >
            Portlethen</option>
                                            <option value="Portree"
            >
            Portree</option>
                                            <option value="Portsmouth"
            >
            Portsmouth</option>
                                            <option value="Potters Bar"
            >
            Potters Bar</option>
                                            <option value="Potton"
            >
            Potton</option>
                                            <option value="Poulton le Fylde"
            >
            Poulton le Fylde</option>
                                            <option value="Poynton"
            >
            Poynton</option>
                                            <option value="Prees"
            >
            Prees</option>
                                            <option value="Preesall"
            >
            Preesall</option>
                                            <option value="Prescot"
            >
            Prescot</option>
                                            <option value="Prestatyn"
            >
            Prestatyn</option>
                                            <option value="Prestbury"
            >
            Prestbury</option>
                                            <option value="Presteign"
            >
            Presteign</option>
                                            <option value="Preston"
            >
            Preston</option>
                                            <option value="Preston"
            >
            Preston</option>
                                            <option value="Prestonpans"
            >
            Prestonpans</option>
                                            <option value="Prestwich"
            >
            Prestwich</option>
                                            <option value="Prestwick"
            >
            Prestwick</option>
                                            <option value="Princes Risborough"
            >
            Princes Risborough</option>
                                            <option value="Prudhoe"
            >
            Prudhoe</option>
                                            <option value="Pucklechurch"
            >
            Pucklechurch</option>
                                            <option value="Pudsey"
            >
            Pudsey</option>
                                            <option value="Pulborough"
            >
            Pulborough</option>
                                            <option value="Purton"
            >
            Purton</option>
                                            <option value="Pwllheli"
            >
            Pwllheli</option>
                                            <option value="Pyle"
            >
            Pyle</option>
                                            <option value="Pyrford"
            >
            Pyrford</option>
                                            <option value="Quarrington"
            >
            Quarrington</option>
                                            <option value="Quedgeley"
            >
            Quedgeley</option>
                                            <option value="Queenborough"
            >
            Queenborough</option>
                                            <option value="Queensbury"
            >
            Queensbury</option>
                                            <option value="Queensferry"
            >
            Queensferry</option>
                                            <option value="Quorndon"
            >
            Quorndon</option>
                                            <option value="Radcliffe"
            >
            Radcliffe</option>
                                            <option value="Radcliffe on Trent"
            >
            Radcliffe on Trent</option>
                                            <option value="Radlett"
            >
            Radlett</option>
                                            <option value="Radley"
            >
            Radley</option>
                                            <option value="Radstock"
            >
            Radstock</option>
                                            <option value="Rainford"
            >
            Rainford</option>
                                            <option value="Rainham"
            >
            Rainham</option>
                                            <option value="Rainhill"
            >
            Rainhill</option>
                                            <option value="Rainow"
            >
            Rainow</option>
                                            <option value="Rainworth"
            >
            Rainworth</option>
                                            <option value="Ramsbottom"
            >
            Ramsbottom</option>
                                            <option value="Ramsey"
            >
            Ramsey</option>
                                            <option value="Ramsgate"
            >
            Ramsgate</option>
                                            <option value="Raunds"
            >
            Raunds</option>
                                            <option value="Rawmarsh"
            >
            Rawmarsh</option>
                                            <option value="Rawtenstall"
            >
            Rawtenstall</option>
                                            <option value="Rayleigh"
            >
            Rayleigh</option>
                                            <option value="Raynes Park"
            >
            Raynes Park</option>
                                            <option value="Reading"
            >
            Reading</option>
                                            <option value="Redbourn"
            >
            Redbourn</option>
                                            <option value="Redcar"
            >
            Redcar</option>
                                            <option value="Reddish"
            >
            Reddish</option>
                                            <option value="Redditch"
            >
            Redditch</option>
                                            <option value="Redhill"
            >
            Redhill</option>
                                            <option value="Redlynch"
            >
            Redlynch</option>
                                            <option value="Redruth"
            >
            Redruth</option>
                                            <option value="Reepham"
            >
            Reepham</option>
                                            <option value="Reigate"
            >
            Reigate</option>
                                            <option value="Rendlesham"
            >
            Rendlesham</option>
                                            <option value="Renfrew"
            >
            Renfrew</option>
                                            <option value="Repton"
            >
            Repton</option>
                                            <option value="Reydon"
            >
            Reydon</option>
                                            <option value="Rhondda"
            >
            Rhondda</option>
                                            <option value="Rhoose"
            >
            Rhoose</option>
                                            <option value="Rhôs-on-Sea"
            >
            Rhôs-on-Sea</option>
                                            <option value="Rhosllanerchrugog"
            >
            Rhosllanerchrugog</option>
                                            <option value="Rhuddlan"
            >
            Rhuddlan</option>
                                            <option value="Rhuthun"
            >
            Rhuthun</option>
                                            <option value="Rhyl"
            >
            Rhyl</option>
                                            <option value="Rhymney"
            >
            Rhymney</option>
                                            <option value="Richmond"
            >
            Richmond</option>
                                            <option value="Richmond"
            >
            Richmond</option>
                                            <option value="Rickmansworth"
            >
            Rickmansworth</option>
                                            <option value="Ringmer"
            >
            Ringmer</option>
                                            <option value="Ringwood"
            >
            Ringwood</option>
                                            <option value="Ripley"
            >
            Ripley</option>
                                            <option value="Ripon"
            >
            Ripon</option>
                                            <option value="Ripponden"
            >
            Ripponden</option>
                                            <option value="Risca"
            >
            Risca</option>
                                            <option value="Rishton"
            >
            Rishton</option>
                                            <option value="River"
            >
            River</option>
                                            <option value="Robertsbridge"
            >
            Robertsbridge</option>
                                            <option value="Roby"
            >
            Roby</option>
                                            <option value="Rochdale"
            >
            Rochdale</option>
                                            <option value="Rochester"
            >
            Rochester</option>
                                            <option value="Rochford"
            >
            Rochford</option>
                                            <option value="Rock Ferry"
            >
            Rock Ferry</option>
                                            <option value="Rogerstone"
            >
            Rogerstone</option>
                                            <option value="Romford"
            >
            Romford</option>
                                            <option value="Romsey"
            >
            Romsey</option>
                                            <option value="Roose"
            >
            Roose</option>
                                            <option value="Ross on Wye"
            >
            Ross on Wye</option>
                                            <option value="Rossett"
            >
            Rossett</option>
                                            <option value="Rossington"
            >
            Rossington</option>
                                            <option value="Rosyth"
            >
            Rosyth</option>
                                            <option value="Rotherfield"
            >
            Rotherfield</option>
                                            <option value="Rotherham"
            >
            Rotherham</option>
                                            <option value="Rothesay"
            >
            Rothesay</option>
                                            <option value="Rothley"
            >
            Rothley</option>
                                            <option value="Rothwell"
            >
            Rothwell</option>
                                            <option value="Rothwell"
            >
            Rothwell</option>
                                            <option value="Rottingdean"
            >
            Rottingdean</option>
                                            <option value="Roundway"
            >
            Roundway</option>
                                            <option value="Rowlands Castle"
            >
            Rowlands Castle</option>
                                            <option value="Rowlands Gill"
            >
            Rowlands Gill</option>
                                            <option value="Rowley Regis"
            >
            Rowley Regis</option>
                                            <option value="Royal Leamington Spa"
            >
            Royal Leamington Spa</option>
                                            <option value="Royal Tunbridge Wells"
            >
            Royal Tunbridge Wells</option>
                                            <option value="Royal Wootton Bassett"
            >
            Royal Wootton Bassett</option>
                                            <option value="Royston"
            >
            Royston</option>
                                            <option value="Royston"
            >
            Royston</option>
                                            <option value="Royton"
            >
            Royton</option>
                                            <option value="Ruabon"
            >
            Ruabon</option>
                                            <option value="Ruddington"
            >
            Ruddington</option>
                                            <option value="Rudgwick"
            >
            Rudgwick</option>
                                            <option value="Rugby"
            >
            Rugby</option>
                                            <option value="Rugeley"
            >
            Rugeley</option>
                                            <option value="Ruislip"
            >
            Ruislip</option>
                                            <option value="Runcorn"
            >
            Runcorn</option>
                                            <option value="Rushall"
            >
            Rushall</option>
                                            <option value="Rushden"
            >
            Rushden</option>
                                            <option value="Ruskington"
            >
            Ruskington</option>
                                            <option value="Rustington"
            >
            Rustington</option>
                                            <option value="Rutherglen"
            >
            Rutherglen</option>
                                            <option value="Ryde"
            >
            Ryde</option>
                                            <option value="Rye"
            >
            Rye</option>
                                            <option value="Ryhill"
            >
            Ryhill</option>
                                            <option value="Ryhope"
            >
            Ryhope</option>
                                            <option value="Sacriston"
            >
            Sacriston</option>
                                            <option value="Saffron Walden"
            >
            Saffron Walden</option>
                                            <option value="Saint Agnes"
            >
            Saint Agnes</option>
                                            <option value="Saint Albans"
            >
            Saint Albans</option>
                                            <option value="Saint Andrews"
            >
            Saint Andrews</option>
                                            <option value="Saint Asaph"
            >
            Saint Asaph</option>
                                            <option value="Saint Athan"
            >
            Saint Athan</option>
                                            <option value="Saint Austell"
            >
            Saint Austell</option>
                                            <option value="Saint Blazey"
            >
            Saint Blazey</option>
                                            <option value="Saint Budeaux"
            >
            Saint Budeaux</option>
                                            <option value="Saint Clears"
            >
            Saint Clears</option>
                                            <option value="Saint Columb Major"
            >
            Saint Columb Major</option>
                                            <option value="Saint Dennis"
            >
            Saint Dennis</option>
                                            <option value="Saint Helens"
            >
            Saint Helens</option>
                                            <option value="Saint Ives"
            >
            Saint Ives</option>
                                            <option value="Saint Ives"
            >
            Saint Ives</option>
                                            <option value="Saint Margaret’s at Cliffe"
            >
            Saint Margaret’s at Cliffe</option>
                                            <option value="Saint Neots"
            >
            Saint Neots</option>
                                            <option value="Saint Osyth"
            >
            Saint Osyth</option>
                                            <option value="Saint Peters"
            >
            Saint Peters</option>
                                            <option value="Sale"
            >
            Sale</option>
                                            <option value="Salford"
            >
            Salford</option>
                                            <option value="Salisbury"
            >
            Salisbury</option>
                                            <option value="Saltash"
            >
            Saltash</option>
                                            <option value="Saltburn-by-the-Sea"
            >
            Saltburn-by-the-Sea</option>
                                            <option value="Saltcoats"
            >
            Saltcoats</option>
                                            <option value="Saltford"
            >
            Saltford</option>
                                            <option value="Sandbach"
            >
            Sandbach</option>
                                            <option value="Sanderstead"
            >
            Sanderstead</option>
                                            <option value="Sandgate"
            >
            Sandgate</option>
                                            <option value="Sandhurst"
            >
            Sandhurst</option>
                                            <option value="Sandiacre"
            >
            Sandiacre</option>
                                            <option value="Sandown"
            >
            Sandown</option>
                                            <option value="Sandridge"
            >
            Sandridge</option>
                                            <option value="Sandwich"
            >
            Sandwich</option>
                                            <option value="Sandy"
            >
            Sandy</option>
                                            <option value="Sandycroft"
            >
            Sandycroft</option>
                                            <option value="Sauchie"
            >
            Sauchie</option>
                                            <option value="Saughall"
            >
            Saughall</option>
                                            <option value="Saundersfoot"
            >
            Saundersfoot</option>
                                            <option value="Sawbridgeworth"
            >
            Sawbridgeworth</option>
                                            <option value="Sawley"
            >
            Sawley</option>
                                            <option value="Sawston"
            >
            Sawston</option>
                                            <option value="Sawtry"
            >
            Sawtry</option>
                                            <option value="Saxilby"
            >
            Saxilby</option>
                                            <option value="Saxmundham"
            >
            Saxmundham</option>
                                            <option value="Scarborough"
            >
            Scarborough</option>
                                            <option value="Scarisbrick"
            >
            Scarisbrick</option>
                                            <option value="Scartho"
            >
            Scartho</option>
                                            <option value="Scotter"
            >
            Scotter</option>
                                            <option value="Scunthorpe"
            >
            Scunthorpe</option>
                                            <option value="Seacombe"
            >
            Seacombe</option>
                                            <option value="Seacroft"
            >
            Seacroft</option>
                                            <option value="Seaford"
            >
            Seaford</option>
                                            <option value="Seaham"
            >
            Seaham</option>
                                            <option value="Sealand"
            >
            Sealand</option>
                                            <option value="Seamer"
            >
            Seamer</option>
                                            <option value="Seasalter"
            >
            Seasalter</option>
                                            <option value="Seaton"
            >
            Seaton</option>
                                            <option value="Seaton Carew"
            >
            Seaton Carew</option>
                                            <option value="Seaton Delaval"
            >
            Seaton Delaval</option>
                                            <option value="Sedbergh"
            >
            Sedbergh</option>
                                            <option value="Sedgefield"
            >
            Sedgefield</option>
                                            <option value="Sedgley"
            >
            Sedgley</option>
                                            <option value="Selby"
            >
            Selby</option>
                                            <option value="Selkirk"
            >
            Selkirk</option>
                                            <option value="Selsey"
            >
            Selsey</option>
                                            <option value="Selston"
            >
            Selston</option>
                                            <option value="Send"
            >
            Send</option>
                                            <option value="Settle"
            >
            Settle</option>
                                            <option value="Sevenoaks"
            >
            Sevenoaks</option>
                                            <option value="Shaftesbury"
            >
            Shaftesbury</option>
                                            <option value="Shalford"
            >
            Shalford</option>
                                            <option value="Shanklin"
            >
            Shanklin</option>
                                            <option value="Shawbury"
            >
            Shawbury</option>
                                            <option value="Shawforth"
            >
            Shawforth</option>
                                            <option value="Shedfield"
            >
            Shedfield</option>
                                            <option value="Sheering"
            >
            Sheering</option>
                                            <option value="Sheerness"
            >
            Sheerness</option>
                                            <option value="Sheffield"
            >
            Sheffield</option>
                                            <option value="Shefford"
            >
            Shefford</option>
                                            <option value="Sheldon"
            >
            Sheldon</option>
                                            <option value="Shelford"
            >
            Shelford</option>
                                            <option value="Shenfield"
            >
            Shenfield</option>
                                            <option value="Shenley"
            >
            Shenley</option>
                                            <option value="Shenley Brook End"
            >
            Shenley Brook End</option>
                                            <option value="Shenley Church End"
            >
            Shenley Church End</option>
                                            <option value="Shenstone"
            >
            Shenstone</option>
                                            <option value="Shepperton"
            >
            Shepperton</option>
                                            <option value="Shepshed"
            >
            Shepshed</option>
                                            <option value="Shepton Mallet"
            >
            Shepton Mallet</option>
                                            <option value="Sherborne"
            >
            Sherborne</option>
                                            <option value="Sherborne Saint John"
            >
            Sherborne Saint John</option>
                                            <option value="Sherburn in Elmet"
            >
            Sherburn in Elmet</option>
                                            <option value="Sheriff Hill"
            >
            Sheriff Hill</option>
                                            <option value="Sheringham"
            >
            Sheringham</option>
                                            <option value="Shevington"
            >
            Shevington</option>
                                            <option value="Shifnal"
            >
            Shifnal</option>
                                            <option value="Shildon"
            >
            Shildon</option>
                                            <option value="Shinfield"
            >
            Shinfield</option>
                                            <option value="Shipley"
            >
            Shipley</option>
                                            <option value="Shipston on Stour"
            >
            Shipston on Stour</option>
                                            <option value="Shirebrook"
            >
            Shirebrook</option>
                                            <option value="Shiremoor"
            >
            Shiremoor</option>
                                            <option value="Shirland"
            >
            Shirland</option>
                                            <option value="Shirley"
            >
            Shirley</option>
                                            <option value="Shoeburyness"
            >
            Shoeburyness</option>
                                            <option value="Shoreham-by-Sea"
            >
            Shoreham-by-Sea</option>
                                            <option value="Shotley Bridge"
            >
            Shotley Bridge</option>
                                            <option value="Shrewsbury"
            >
            Shrewsbury</option>
                                            <option value="Sible Hedingham"
            >
            Sible Hedingham</option>
                                            <option value="Sidcup"
            >
            Sidcup</option>
                                            <option value="Sidley"
            >
            Sidley</option>
                                            <option value="Sidmouth"
            >
            Sidmouth</option>
                                            <option value="Sileby"
            >
            Sileby</option>
                                            <option value="Silkstone"
            >
            Silkstone</option>
                                            <option value="Silloth"
            >
            Silloth</option>
                                            <option value="Silsden"
            >
            Silsden</option>
                                            <option value="Silverdale"
            >
            Silverdale</option>
                                            <option value="Siston"
            >
            Siston</option>
                                            <option value="Sittingbourne"
            >
            Sittingbourne</option>
                                            <option value="Skegness"
            >
            Skegness</option>
                                            <option value="Skellingthorpe"
            >
            Skellingthorpe</option>
                                            <option value="Skellow"
            >
            Skellow</option>
                                            <option value="Skelmanthorpe"
            >
            Skelmanthorpe</option>
                                            <option value="Skelmersdale"
            >
            Skelmersdale</option>
                                            <option value="Skelton"
            >
            Skelton</option>
                                            <option value="Skewen"
            >
            Skewen</option>
                                            <option value="Skipton"
            >
            Skipton</option>
                                            <option value="Sleaford"
            >
            Sleaford</option>
                                            <option value="Slough"
            >
            Slough</option>
                                            <option value="Small Heath"
            >
            Small Heath</option>
                                            <option value="Smalley"
            >
            Smalley</option>
                                            <option value="Smethwick"
            >
            Smethwick</option>
                                            <option value="Snaresbrook"
            >
            Snaresbrook</option>
                                            <option value="Snettisham"
            >
            Snettisham</option>
                                            <option value="Snodland"
            >
            Snodland</option>
                                            <option value="Soham"
            >
            Soham</option>
                                            <option value="Solihull"
            >
            Solihull</option>
                                            <option value="Sollom"
            >
            Sollom</option>
                                            <option value="Somersham"
            >
            Somersham</option>
                                            <option value="Somerton"
            >
            Somerton</option>
                                            <option value="Sompting"
            >
            Sompting</option>
                                            <option value="South Brent"
            >
            South Brent</option>
                                            <option value="South Cave"
            >
            South Cave</option>
                                            <option value="South Cerney"
            >
            South Cerney</option>
                                            <option value="South Elmsall"
            >
            South Elmsall</option>
                                            <option value="South Hanningfield"
            >
            South Hanningfield</option>
                                            <option value="South Hetton"
            >
            South Hetton</option>
                                            <option value="South Molton"
            >
            South Molton</option>
                                            <option value="South Normanton"
            >
            South Normanton</option>
                                            <option value="South Ockendon"
            >
            South Ockendon</option>
                                            <option value="South Petherton"
            >
            South Petherton</option>
                                            <option value="South Shields"
            >
            South Shields</option>
                                            <option value="South Wootton"
            >
            South Wootton</option>
                                            <option value="Southall"
            >
            Southall</option>
                                            <option value="Southam"
            >
            Southam</option>
                                            <option value="Southampton"
            >
            Southampton</option>
                                            <option value="Southborough"
            >
            Southborough</option>
                                            <option value="Southbourne"
            >
            Southbourne</option>
                                            <option value="Southbourne"
            >
            Southbourne</option>
                                            <option value="Southgate"
            >
            Southgate</option>
                                            <option value="Southminster"
            >
            Southminster</option>
                                            <option value="Southport"
            >
            Southport</option>
                                            <option value="Southwater"
            >
            Southwater</option>
                                            <option value="Southwell"
            >
            Southwell</option>
                                            <option value="Southwick"
            >
            Southwick</option>
                                            <option value="Southwick"
            >
            Southwick</option>
                                            <option value="Sowerby Bridge"
            >
            Sowerby Bridge</option>
                                            <option value="Spalding"
            >
            Spalding</option>
                                            <option value="Speen"
            >
            Speen</option>
                                            <option value="Speldhurst"
            >
            Speldhurst</option>
                                            <option value="Spennymoor"
            >
            Spennymoor</option>
                                            <option value="Spilsby"
            >
            Spilsby</option>
                                            <option value="Spitalfields"
            >
            Spitalfields</option>
                                            <option value="Spixworth"
            >
            Spixworth</option>
                                            <option value="Spondon"
            >
            Spondon</option>
                                            <option value="Stafford"
            >
            Stafford</option>
                                            <option value="Staines-upon-Thames"
            >
            Staines-upon-Thames</option>
                                            <option value="Stainforth"
            >
            Stainforth</option>
                                            <option value="Stainland"
            >
            Stainland</option>
                                            <option value="Stainton"
            >
            Stainton</option>
                                            <option value="Stalbridge"
            >
            Stalbridge</option>
                                            <option value="Stalham"
            >
            Stalham</option>
                                            <option value="Stalybridge"
            >
            Stalybridge</option>
                                            <option value="Stamford"
            >
            Stamford</option>
                                            <option value="Stamford Bridge"
            >
            Stamford Bridge</option>
                                            <option value="Standish"
            >
            Standish</option>
                                            <option value="Standon"
            >
            Standon</option>
                                            <option value="Stanford le Hope"
            >
            Stanford le Hope</option>
                                            <option value="Stanley"
            >
            Stanley</option>
                                            <option value="Stanmore"
            >
            Stanmore</option>
                                            <option value="Stannington"
            >
            Stannington</option>
                                            <option value="Stansted Mountfitchet"
            >
            Stansted Mountfitchet</option>
                                            <option value="Stanton"
            >
            Stanton</option>
                                            <option value="Stanwell"
            >
            Stanwell</option>
                                            <option value="Stapleford"
            >
            Stapleford</option>
                                            <option value="Staplehurst"
            >
            Staplehurst</option>
                                            <option value="Staveley"
            >
            Staveley</option>
                                            <option value="Steeton"
            >
            Steeton</option>
                                            <option value="Stepney"
            >
            Stepney</option>
                                            <option value="Stepps"
            >
            Stepps</option>
                                            <option value="Stevenston"
            >
            Stevenston</option>
                                            <option value="Stewarton"
            >
            Stewarton</option>
                                            <option value="Steyning"
            >
            Steyning</option>
                                            <option value="Steynton"
            >
            Steynton</option>
                                            <option value="Stirling"
            >
            Stirling</option>
                                            <option value="Stockport"
            >
            Stockport</option>
                                            <option value="Stocksbridge"
            >
            Stocksbridge</option>
                                            <option value="Stocksfield"
            >
            Stocksfield</option>
                                            <option value="Stockton-on-Tees"
            >
            Stockton-on-Tees</option>
                                            <option value="Stoke Gifford"
            >
            Stoke Gifford</option>
                                            <option value="Stoke Mandeville"
            >
            Stoke Mandeville</option>
                                            <option value="Stoke Poges"
            >
            Stoke Poges</option>
                                            <option value="Stoke Prior"
            >
            Stoke Prior</option>
                                            <option value="Stoke-on-Trent"
            >
            Stoke-on-Trent</option>
                                            <option value="Stokenchurch"
            >
            Stokenchurch</option>
                                            <option value="Stokesley"
            >
            Stokesley</option>
                                            <option value="Stone"
            >
            Stone</option>
                                            <option value="Stone"
            >
            Stone</option>
                                            <option value="Stonehaven"
            >
            Stonehaven</option>
                                            <option value="Stonehouse"
            >
            Stonehouse</option>
                                            <option value="Stonehouse"
            >
            Stonehouse</option>
                                            <option value="Stoneleigh"
            >
            Stoneleigh</option>
                                            <option value="Stonewood"
            >
            Stonewood</option>
                                            <option value="Stoney Stanton"
            >
            Stoney Stanton</option>
                                            <option value="Stony Stratford"
            >
            Stony Stratford</option>
                                            <option value="Stopsley"
            >
            Stopsley</option>
                                            <option value="Stornoway"
            >
            Stornoway</option>
                                            <option value="Stotfold"
            >
            Stotfold</option>
                                            <option value="Stourbridge"
            >
            Stourbridge</option>
                                            <option value="Stowmarket"
            >
            Stowmarket</option>
                                            <option value="Stranraer"
            >
            Stranraer</option>
                                            <option value="Stratfield Mortimer"
            >
            Stratfield Mortimer</option>
                                            <option value="Stratford"
            >
            Stratford</option>
                                            <option value="Stratford-upon-Avon"
            >
            Stratford-upon-Avon</option>
                                            <option value="Strathaven"
            >
            Strathaven</option>
                                            <option value="Stratton Saint Margaret"
            >
            Stratton Saint Margaret</option>
                                            <option value="Streatham"
            >
            Streatham</option>
                                            <option value="Street"
            >
            Street</option>
                                            <option value="Streetly"
            >
            Streetly</option>
                                            <option value="Strensall"
            >
            Strensall</option>
                                            <option value="Stretford"
            >
            Stretford</option>
                                            <option value="Strood"
            >
            Strood</option>
                                            <option value="Stroud"
            >
            Stroud</option>
                                            <option value="Studley"
            >
            Studley</option>
                                            <option value="Sturminster Newton"
            >
            Sturminster Newton</option>
                                            <option value="Sturry"
            >
            Sturry</option>
                                            <option value="Sudbury"
            >
            Sudbury</option>
                                            <option value="Sudbury"
            >
            Sudbury</option>
                                            <option value="Sully"
            >
            Sully</option>
                                            <option value="Sunbury"
            >
            Sunbury</option>
                                            <option value="Sunderland"
            >
            Sunderland</option>
                                            <option value="Sundon"
            >
            Sundon</option>
                                            <option value="Sunningdale"
            >
            Sunningdale</option>
                                            <option value="Sunninghill"
            >
            Sunninghill</option>
                                            <option value="Surbiton"
            >
            Surbiton</option>
                                            <option value="Sutton"
            >
            Sutton</option>
                                            <option value="Sutton Bridge"
            >
            Sutton Bridge</option>
                                            <option value="Sutton Coldfield"
            >
            Sutton Coldfield</option>
                                            <option value="Sutton in Ashfield"
            >
            Sutton in Ashfield</option>
                                            <option value="Sutton on Hull"
            >
            Sutton on Hull</option>
                                            <option value="Sutton-in-Craven"
            >
            Sutton-in-Craven</option>
                                            <option value="Swadlincote"
            >
            Swadlincote</option>
                                            <option value="Swaffham"
            >
            Swaffham</option>
                                            <option value="Swallownest"
            >
            Swallownest</option>
                                            <option value="Swalwell"
            >
            Swalwell</option>
                                            <option value="Swanage"
            >
            Swanage</option>
                                            <option value="Swanland"
            >
            Swanland</option>
                                            <option value="Swanmore"
            >
            Swanmore</option>
                                            <option value="Swanscombe"
            >
            Swanscombe</option>
                                            <option value="Swanwick"
            >
            Swanwick</option>
                                            <option value="Sway"
            >
            Sway</option>
                                            <option value="Swillington"
            >
            Swillington</option>
                                            <option value="Swindon"
            >
            Swindon</option>
                                            <option value="Swinton"
            >
            Swinton</option>
                                            <option value="Swinton"
            >
            Swinton</option>
                                            <option value="Swynnerton"
            >
            Swynnerton</option>
                                            <option value="Syston"
            >
            Syston</option>
                                            <option value="Tadcaster"
            >
            Tadcaster</option>
                                            <option value="Tadley"
            >
            Tadley</option>
                                            <option value="Tadworth"
            >
            Tadworth</option>
                                            <option value="Taff’s Well"
            >
            Taff’s Well</option>
                                            <option value="Taibach"
            >
            Taibach</option>
                                            <option value="Tain"
            >
            Tain</option>
                                            <option value="Takeley"
            >
            Takeley</option>
                                            <option value="Tanfield"
            >
            Tanfield</option>
                                            <option value="Tangmere"
            >
            Tangmere</option>
                                            <option value="Tardebigge"
            >
            Tardebigge</option>
                                            <option value="Tarleton"
            >
            Tarleton</option>
                                            <option value="Tarporley"
            >
            Tarporley</option>
                                            <option value="Tarvin"
            >
            Tarvin</option>
                                            <option value="Tattershall"
            >
            Tattershall</option>
                                            <option value="Taunton"
            >
            Taunton</option>
                                            <option value="Tavistock"
            >
            Tavistock</option>
                                            <option value="Tayport"
            >
            Tayport</option>
                                            <option value="Teignmouth"
            >
            Teignmouth</option>
                                            <option value="Telford"
            >
            Telford</option>
                                            <option value="Tenbury"
            >
            Tenbury</option>
                                            <option value="Tenterden"
            >
            Tenterden</option>
                                            <option value="Terrington Saint Clement"
            >
            Terrington Saint Clement</option>
                                            <option value="Tetbury"
            >
            Tetbury</option>
                                            <option value="Tettenhall"
            >
            Tettenhall</option>
                                            <option value="Teversham"
            >
            Teversham</option>
                                            <option value="Tewkesbury"
            >
            Tewkesbury</option>
                                            <option value="Teynham"
            >
            Teynham</option>
                                            <option value="Thame"
            >
            Thame</option>
                                            <option value="Thames Ditton"
            >
            Thames Ditton</option>
                                            <option value="Thatcham"
            >
            Thatcham</option>
                                            <option value="Thatto Heath"
            >
            Thatto Heath</option>
                                            <option value="Thaxted"
            >
            Thaxted</option>
                                            <option value="The Mumbles"
            >
            The Mumbles</option>
                                            <option value="Theale"
            >
            Theale</option>
                                            <option value="Thetford"
            >
            Thetford</option>
                                            <option value="Theydon Bois"
            >
            Theydon Bois</option>
                                            <option value="Thirsk"
            >
            Thirsk</option>
                                            <option value="Thornaby on Tees"
            >
            Thornaby on Tees</option>
                                            <option value="Thornbury"
            >
            Thornbury</option>
                                            <option value="Thorngumbald"
            >
            Thorngumbald</option>
                                            <option value="Thornhill"
            >
            Thornhill</option>
                                            <option value="Thornton"
            >
            Thornton</option>
                                            <option value="Thornton Heath"
            >
            Thornton Heath</option>
                                            <option value="Thorpe"
            >
            Thorpe</option>
                                            <option value="Thorpe Saint Andrew"
            >
            Thorpe Saint Andrew</option>
                                            <option value="Thrapston"
            >
            Thrapston</option>
                                            <option value="Thringstone"
            >
            Thringstone</option>
                                            <option value="Thrybergh"
            >
            Thrybergh</option>
                                            <option value="Thundersley"
            >
            Thundersley</option>
                                            <option value="Thurso"
            >
            Thurso</option>
                                            <option value="Thurston"
            >
            Thurston</option>
                                            <option value="Tibshelf"
            >
            Tibshelf</option>
                                            <option value="Ticehurst"
            >
            Ticehurst</option>
                                            <option value="Tickhill"
            >
            Tickhill</option>
                                            <option value="Tidenham"
            >
            Tidenham</option>
                                            <option value="Tilbury"
            >
            Tilbury</option>
                                            <option value="Tilehurst"
            >
            Tilehurst</option>
                                            <option value="Tillicoultry"
            >
            Tillicoultry</option>
                                            <option value="Timperley"
            >
            Timperley</option>
                                            <option value="Timsbury"
            >
            Timsbury</option>
                                            <option value="Tipton"
            >
            Tipton</option>
                                            <option value="Tiptree"
            >
            Tiptree</option>
                                            <option value="Titchfield"
            >
            Titchfield</option>
                                            <option value="Tiverton"
            >
            Tiverton</option>
                                            <option value="Toddington"
            >
            Toddington</option>
                                            <option value="Todmorden"
            >
            Todmorden</option>
                                            <option value="Tollesbury"
            >
            Tollesbury</option>
                                            <option value="Tonbridge"
            >
            Tonbridge</option>
                                            <option value="Tonypandy"
            >
            Tonypandy</option>
                                            <option value="Tonyrefail"
            >
            Tonyrefail</option>
                                            <option value="Topsham"
            >
            Topsham</option>
                                            <option value="Torpoint"
            >
            Torpoint</option>
                                            <option value="Torquay"
            >
            Torquay</option>
                                            <option value="Torrisholme"
            >
            Torrisholme</option>
                                            <option value="Torton"
            >
            Torton</option>
                                            <option value="Totland"
            >
            Totland</option>
                                            <option value="Totnes"
            >
            Totnes</option>
                                            <option value="Toton"
            >
            Toton</option>
                                            <option value="Tottenham"
            >
            Tottenham</option>
                                            <option value="Totteridge"
            >
            Totteridge</option>
                                            <option value="Tottington"
            >
            Tottington</option>
                                            <option value="Towcester"
            >
            Towcester</option>
                                            <option value="Towyn"
            >
            Towyn</option>
                                            <option value="Tranent"
            >
            Tranent</option>
                                            <option value="Tredegar"
            >
            Tredegar</option>
                                            <option value="Treeton"
            >
            Treeton</option>
                                            <option value="Treforest"
            >
            Treforest</option>
                                            <option value="Treharris"
            >
            Treharris</option>
                                            <option value="Treherbert"
            >
            Treherbert</option>
                                            <option value="Trentham"
            >
            Trentham</option>
                                            <option value="Treorchy"
            >
            Treorchy</option>
                                            <option value="Trimdon"
            >
            Trimdon</option>
                                            <option value="Trimsaran"
            >
            Trimsaran</option>
                                            <option value="Tring"
            >
            Tring</option>
                                            <option value="Troon"
            >
            Troon</option>
                                            <option value="Trowbridge"
            >
            Trowbridge</option>
                                            <option value="Trumpington"
            >
            Trumpington</option>
                                            <option value="Truro"
            >
            Truro</option>
                                            <option value="Turriff"
            >
            Turriff</option>
                                            <option value="Tutbury"
            >
            Tutbury</option>
                                            <option value="Tuxford"
            >
            Tuxford</option>
                                            <option value="Twickenham"
            >
            Twickenham</option>
                                            <option value="Twyford"
            >
            Twyford</option>
                                            <option value="Tyldesley"
            >
            Tyldesley</option>
                                            <option value="Tynemouth"
            >
            Tynemouth</option>
                                            <option value="Uckfield"
            >
            Uckfield</option>
                                            <option value="Uddingston"
            >
            Uddingston</option>
                                            <option value="Ulverston"
            >
            Ulverston</option>
                                            <option value="Upholland"
            >
            Upholland</option>
                                            <option value="Upminster"
            >
            Upminster</option>
                                            <option value="Upper Beeding"
            >
            Upper Beeding</option>
                                            <option value="Uppingham"
            >
            Uppingham</option>
                                            <option value="Upton"
            >
            Upton</option>
                                            <option value="Upton"
            >
            Upton</option>
                                            <option value="Upton upon Severn"
            >
            Upton upon Severn</option>
                                            <option value="Upwell"
            >
            Upwell</option>
                                            <option value="Urmston"
            >
            Urmston</option>
                                            <option value="Ushaw Moor"
            >
            Ushaw Moor</option>
                                            <option value="Usk"
            >
            Usk</option>
                                            <option value="Usworth"
            >
            Usworth</option>
                                            <option value="Utley"
            >
            Utley</option>
                                            <option value="Uttoxeter"
            >
            Uttoxeter</option>
                                            <option value="Uxbridge"
            >
            Uxbridge</option>
                                            <option value="Ventnor"
            >
            Ventnor</option>
                                            <option value="Verwood"
            >
            Verwood</option>
                                            <option value="Waddington"
            >
            Waddington</option>
                                            <option value="Wadebridge"
            >
            Wadebridge</option>
                                            <option value="Wadhurst"
            >
            Wadhurst</option>
                                            <option value="Wadsley"
            >
            Wadsley</option>
                                            <option value="Wakefield"
            >
            Wakefield</option>
                                            <option value="Walkden"
            >
            Walkden</option>
                                            <option value="Wallasey"
            >
            Wallasey</option>
                                            <option value="Wallingford"
            >
            Wallingford</option>
                                            <option value="Wallington"
            >
            Wallington</option>
                                            <option value="Wallsend"
            >
            Wallsend</option>
                                            <option value="Walmer"
            >
            Walmer</option>
                                            <option value="Walsall"
            >
            Walsall</option>
                                            <option value="Walsall Wood"
            >
            Walsall Wood</option>
                                            <option value="Waltham"
            >
            Waltham</option>
                                            <option value="Waltham Abbey"
            >
            Waltham Abbey</option>
                                            <option value="Waltham Cross"
            >
            Waltham Cross</option>
                                            <option value="Walthamstow"
            >
            Walthamstow</option>
                                            <option value="Walton le Dale"
            >
            Walton le Dale</option>
                                            <option value="Walton upon Thames"
            >
            Walton upon Thames</option>
                                            <option value="Wandsworth"
            >
            Wandsworth</option>
                                            <option value="Wanstead"
            >
            Wanstead</option>
                                            <option value="Wantage"
            >
            Wantage</option>
                                            <option value="Warboys"
            >
            Warboys</option>
                                            <option value="Wardle"
            >
            Wardle</option>
                                            <option value="Ware"
            >
            Ware</option>
                                            <option value="Wareham"
            >
            Wareham</option>
                                            <option value="Warfield"
            >
            Warfield</option>
                                            <option value="Warlingham"
            >
            Warlingham</option>
                                            <option value="Warminster"
            >
            Warminster</option>
                                            <option value="Warmsworth"
            >
            Warmsworth</option>
                                            <option value="Warrenpoint"
            >
            Warrenpoint</option>
                                            <option value="Warrington"
            >
            Warrington</option>
                                            <option value="Warsash"
            >
            Warsash</option>
                                            <option value="Warsop"
            >
            Warsop</option>
                                            <option value="Warwick"
            >
            Warwick</option>
                                            <option value="Washingborough"
            >
            Washingborough</option>
                                            <option value="Washington"
            >
            Washington</option>
                                            <option value="Watchet"
            >
            Watchet</option>
                                            <option value="Water Orton"
            >
            Water Orton</option>
                                            <option value="Waterbeach"
            >
            Waterbeach</option>
                                            <option value="Waterlooville"
            >
            Waterlooville</option>
                                            <option value="Watford"
            >
            Watford</option>
                                            <option value="Wath upon Dearne"
            >
            Wath upon Dearne</option>
                                            <option value="Watlington"
            >
            Watlington</option>
                                            <option value="Watton"
            >
            Watton</option>
                                            <option value="Wealdstone"
            >
            Wealdstone</option>
                                            <option value="Weaverham"
            >
            Weaverham</option>
                                            <option value="Wedmore"
            >
            Wedmore</option>
                                            <option value="Wednesfield"
            >
            Wednesfield</option>
                                            <option value="Weedon Beck"
            >
            Weedon Beck</option>
                                            <option value="Wellesbourne Hastings"
            >
            Wellesbourne Hastings</option>
                                            <option value="Welling"
            >
            Welling</option>
                                            <option value="Wellingborough"
            >
            Wellingborough</option>
                                            <option value="Wellington"
            >
            Wellington</option>
                                            <option value="Wellington"
            >
            Wellington</option>
                                            <option value="Wells"
            >
            Wells</option>
                                            <option value="Welshpool"
            >
            Welshpool</option>
                                            <option value="Welton"
            >
            Welton</option>
                                            <option value="Welwyn"
            >
            Welwyn</option>
                                            <option value="Welwyn Garden City"
            >
            Welwyn Garden City</option>
                                            <option value="Wem"
            >
            Wem</option>
                                            <option value="Wembdon"
            >
            Wembdon</option>
                                            <option value="Wembley"
            >
            Wembley</option>
                                            <option value="Wembury"
            >
            Wembury</option>
                                            <option value="Wendover"
            >
            Wendover</option>
                                            <option value="Wendron"
            >
            Wendron</option>
                                            <option value="West Auckland"
            >
            West Auckland</option>
                                            <option value="West Bergholt"
            >
            West Bergholt</option>
                                            <option value="West Boldon"
            >
            West Boldon</option>
                                            <option value="West Bridgford"
            >
            West Bridgford</option>
                                            <option value="West Bromwich"
            >
            West Bromwich</option>
                                            <option value="West Byfleet"
            >
            West Byfleet</option>
                                            <option value="West Calder"
            >
            West Calder</option>
                                            <option value="West Chiltington"
            >
            West Chiltington</option>
                                            <option value="West Coker"
            >
            West Coker</option>
                                            <option value="West Derby"
            >
            West Derby</option>
                                            <option value="West Drayton"
            >
            West Drayton</option>
                                            <option value="West Grinstead"
            >
            West Grinstead</option>
                                            <option value="West Hallam"
            >
            West Hallam</option>
                                            <option value="West Ham"
            >
            West Ham</option>
                                            <option value="West Horsley"
            >
            West Horsley</option>
                                            <option value="West Kilbride"
            >
            West Kilbride</option>
                                            <option value="West Malling"
            >
            West Malling</option>
                                            <option value="West Melton"
            >
            West Melton</option>
                                            <option value="West Mersea"
            >
            West Mersea</option>
                                            <option value="West Monkton"
            >
            West Monkton</option>
                                            <option value="West Parley"
            >
            West Parley</option>
                                            <option value="West Thurrock"
            >
            West Thurrock</option>
                                            <option value="West Wellow"
            >
            West Wellow</option>
                                            <option value="West Wickham"
            >
            West Wickham</option>
                                            <option value="West Wittering"
            >
            West Wittering</option>
                                            <option value="Westbury"
            >
            Westbury</option>
                                            <option value="Westerham"
            >
            Westerham</option>
                                            <option value="Westerleigh"
            >
            Westerleigh</option>
                                            <option value="Westfield"
            >
            Westfield</option>
                                            <option value="Westgate on Sea"
            >
            Westgate on Sea</option>
                                            <option value="Westhoughton"
            >
            Westhoughton</option>
                                            <option value="Weston"
            >
            Weston</option>
                                            <option value="Weston Turville"
            >
            Weston Turville</option>
                                            <option value="Weston-super-Mare"
            >
            Weston-super-Mare</option>
                                            <option value="Wetheral"
            >
            Wetheral</option>
                                            <option value="Wetherby"
            >
            Wetherby</option>
                                            <option value="Weybridge"
            >
            Weybridge</option>
                                            <option value="Weymouth"
            >
            Weymouth</option>
                                            <option value="Whaley Bridge"
            >
            Whaley Bridge</option>
                                            <option value="Whalley"
            >
            Whalley</option>
                                            <option value="Whaplode"
            >
            Whaplode</option>
                                            <option value="Wheathampstead"
            >
            Wheathampstead</option>
                                            <option value="Wheatley"
            >
            Wheatley</option>
                                            <option value="Whetstone"
            >
            Whetstone</option>
                                            <option value="Whickham"
            >
            Whickham</option>
                                            <option value="Whiston"
            >
            Whiston</option>
                                            <option value="Whitburn"
            >
            Whitburn</option>
                                            <option value="Whitby"
            >
            Whitby</option>
                                            <option value="Whitchurch"
            >
            Whitchurch</option>
                                            <option value="Whitchurch"
            >
            Whitchurch</option>
                                            <option value="Whitchurch"
            >
            Whitchurch</option>
                                            <option value="White Waltham"
            >
            White Waltham</option>
                                            <option value="Whitefield"
            >
            Whitefield</option>
                                            <option value="Whitehaven"
            >
            Whitehaven</option>
                                            <option value="Whitley Bay"
            >
            Whitley Bay</option>
                                            <option value="Whitnash"
            >
            Whitnash</option>
                                            <option value="Whitstable"
            >
            Whitstable</option>
                                            <option value="Whittington"
            >
            Whittington</option>
                                            <option value="Whittington"
            >
            Whittington</option>
                                            <option value="Whittington"
            >
            Whittington</option>
                                            <option value="Whittlesey"
            >
            Whittlesey</option>
                                            <option value="Whitwell"
            >
            Whitwell</option>
                                            <option value="Whitwick"
            >
            Whitwick</option>
                                            <option value="Whitworth"
            >
            Whitworth</option>
                                            <option value="Whyteleafe"
            >
            Whyteleafe</option>
                                            <option value="Wibsey"
            >
            Wibsey</option>
                                            <option value="Wick"
            >
            Wick</option>
                                            <option value="Wickersley"
            >
            Wickersley</option>
                                            <option value="Wickford"
            >
            Wickford</option>
                                            <option value="Wickham"
            >
            Wickham</option>
                                            <option value="Widnes"
            >
            Widnes</option>
                                            <option value="Wigan"
            >
            Wigan</option>
                                            <option value="Wigginton"
            >
            Wigginton</option>
                                            <option value="Wigston Magna"
            >
            Wigston Magna</option>
                                            <option value="Wigton"
            >
            Wigton</option>
                                            <option value="Wilford"
            >
            Wilford</option>
                                            <option value="Willaston"
            >
            Willaston</option>
                                            <option value="Willaston"
            >
            Willaston</option>
                                            <option value="Willenhall"
            >
            Willenhall</option>
                                            <option value="Willerby"
            >
            Willerby</option>
                                            <option value="Willesborough"
            >
            Willesborough</option>
                                            <option value="Willingham"
            >
            Willingham</option>
                                            <option value="Willington"
            >
            Willington</option>
                                            <option value="Williton"
            >
            Williton</option>
                                            <option value="Wilmington"
            >
            Wilmington</option>
                                            <option value="Wilmslow"
            >
            Wilmslow</option>
                                            <option value="Wilnecote"
            >
            Wilnecote</option>
                                            <option value="Wilpshire"
            >
            Wilpshire</option>
                                            <option value="Wilsden"
            >
            Wilsden</option>
                                            <option value="Wilstead"
            >
            Wilstead</option>
                                            <option value="Wilton"
            >
            Wilton</option>
                                            <option value="Wimbledon"
            >
            Wimbledon</option>
                                            <option value="Wimborne Minster"
            >
            Wimborne Minster</option>
                                            <option value="Wincanton"
            >
            Wincanton</option>
                                            <option value="Winchcomb"
            >
            Winchcomb</option>
                                            <option value="Winchester"
            >
            Winchester</option>
                                            <option value="Windermere"
            >
            Windermere</option>
                                            <option value="Windlesham"
            >
            Windlesham</option>
                                            <option value="Windsor"
            >
            Windsor</option>
                                            <option value="Wing"
            >
            Wing</option>
                                            <option value="Wingate"
            >
            Wingate</option>
                                            <option value="Wingerworth"
            >
            Wingerworth</option>
                                            <option value="Winkfield"
            >
            Winkfield</option>
                                            <option value="Winsford"
            >
            Winsford</option>
                                            <option value="Winterton"
            >
            Winterton</option>
                                            <option value="Winwick"
            >
            Winwick</option>
                                            <option value="Wirksworth"
            >
            Wirksworth</option>
                                            <option value="Wisbech"
            >
            Wisbech</option>
                                            <option value="Wisbech Saint Mary"
            >
            Wisbech Saint Mary</option>
                                            <option value="Wishaw"
            >
            Wishaw</option>
                                            <option value="Wistaston"
            >
            Wistaston</option>
                                            <option value="Witham"
            >
            Witham</option>
                                            <option value="Withernsea"
            >
            Withernsea</option>
                                            <option value="Withnell"
            >
            Withnell</option>
                                            <option value="Withyham"
            >
            Withyham</option>
                                            <option value="Witley"
            >
            Witley</option>
                                            <option value="Witney"
            >
            Witney</option>
                                            <option value="Wiveliscombe"
            >
            Wiveliscombe</option>
                                            <option value="Wivenhoe"
            >
            Wivenhoe</option>
                                            <option value="Woking"
            >
            Woking</option>
                                            <option value="Wokingham"
            >
            Wokingham</option>
                                            <option value="Wollaston"
            >
            Wollaston</option>
                                            <option value="Wollaston"
            >
            Wollaston</option>
                                            <option value="Wolsingham"
            >
            Wolsingham</option>
                                            <option value="Wolston"
            >
            Wolston</option>
                                            <option value="Wolverhampton"
            >
            Wolverhampton</option>
                                            <option value="Wolverton"
            >
            Wolverton</option>
                                            <option value="Wombourn"
            >
            Wombourn</option>
                                            <option value="Wombwell"
            >
            Wombwell</option>
                                            <option value="Wonersh"
            >
            Wonersh</option>
                                            <option value="Wooburn"
            >
            Wooburn</option>
                                            <option value="Wood Green"
            >
            Wood Green</option>
                                            <option value="Woodbridge"
            >
            Woodbridge</option>
                                            <option value="Woodchurch"
            >
            Woodchurch</option>
                                            <option value="Woodhall Spa"
            >
            Woodhall Spa</option>
                                            <option value="Woodhouse"
            >
            Woodhouse</option>
                                            <option value="Woodlesford"
            >
            Woodlesford</option>
                                            <option value="Woodley"
            >
            Woodley</option>
                                            <option value="Woodstock"
            >
            Woodstock</option>
                                            <option value="Woodville"
            >
            Woodville</option>
                                            <option value="Wool"
            >
            Wool</option>
                                            <option value="Woolton"
            >
            Woolton</option>
                                            <option value="Woolwich"
            >
            Woolwich</option>
                                            <option value="Wootton"
            >
            Wootton</option>
                                            <option value="Wootton"
            >
            Wootton</option>
                                            <option value="Wootton"
            >
            Wootton</option>
                                            <option value="Wootton"
            >
            Wootton</option>
                                            <option value="Worcester"
            >
            Worcester</option>
                                            <option value="Worcester Park"
            >
            Worcester Park</option>
                                            <option value="Wordsley"
            >
            Wordsley</option>
                                            <option value="Workington"
            >
            Workington</option>
                                            <option value="Worksop"
            >
            Worksop</option>
                                            <option value="Worlingham"
            >
            Worlingham</option>
                                            <option value="Worplesdon"
            >
            Worplesdon</option>
                                            <option value="Worsborough"
            >
            Worsborough</option>
                                            <option value="Worsley"
            >
            Worsley</option>
                                            <option value="Worth"
            >
            Worth</option>
                                            <option value="Worthing"
            >
            Worthing</option>
                                            <option value="Wotton-under-Edge"
            >
            Wotton-under-Edge</option>
                                            <option value="Wraysbury"
            >
            Wraysbury</option>
                                            <option value="Wrecclesham"
            >
            Wrecclesham</option>
                                            <option value="Wrecsam"
            >
            Wrecsam</option>
                                            <option value="Wrington"
            >
            Wrington</option>
                                            <option value="Writtle"
            >
            Writtle</option>
                                            <option value="Wrockwardine"
            >
            Wrockwardine</option>
                                            <option value="Wroughton"
            >
            Wroughton</option>
                                            <option value="Wyberton"
            >
            Wyberton</option>
                                            <option value="Wyke"
            >
            Wyke</option>
                                            <option value="Wyke Regis"
            >
            Wyke Regis</option>
                                            <option value="Wymondham"
            >
            Wymondham</option>
                                            <option value="Wythenshawe"
            >
            Wythenshawe</option>
                                            <option value="Yapton"
            >
            Yapton</option>
                                            <option value="Yarm"
            >
            Yarm</option>
                                            <option value="Yarnton"
            >
            Yarnton</option>
                                            <option value="Yate"
            >
            Yate</option>
                                            <option value="Yatton"
            >
            Yatton</option>
                                            <option value="Yaxley"
            >
            Yaxley</option>
                                            <option value="Yeadon"
            >
            Yeadon</option>
                                            <option value="Yelverton"
            >
            Yelverton</option>
                                            <option value="Yeovil"
            >
            Yeovil</option>
                                            <option value="Yiewsley"
            >
            Yiewsley</option>
                                            <option value="Ynysddu"
            >
            Ynysddu</option>
                                            <option value="York"
            >
            York</option>
                                            <option value="Ystalyfera"
            >
            Ystalyfera</option>
                                            <option value="Ystrad Mynach"
            >
            Ystrad Mynach</option>
                                            <option value="Ystradgynlais"
            >
            Ystradgynlais</option>    
            </select>';
    }


    public function currencies($ctry)
    {
        $currency = array(
            'AF' => 'AFN',
            'AL' => 'ALL',
            'DZ' => 'DZD',
            'AS' => 'USD',
            'AD' => 'EUR',
            'AO' => 'AOA',
            'AI' => 'XCD',
            'AQ' => 'XCD',
            'AG' => 'XCD',
            'AR' => 'ARS',
            'AM' => 'AMD',
            'AW' => 'AWG',
            'AU' => 'AUD',
            'AT' => 'EUR',
            'AZ' => 'AZN',
            'BS' => 'BSD',
            'BH' => 'BHD',
            'BD' => 'BDT',
            'BB' => 'BBD',
            'BY' => 'BYR',
            'BE' => 'EUR',
            'BZ' => 'BZD',
            'BJ' => 'XOF',
            'BM' => 'BMD',
            'BT' => 'BTN',
            'BO' => 'BOB',
            'BA' => 'BAM',
            'BW' => 'BWP',
            'BV' => 'NOK',
            'BR' => 'BRL',
            'IO' => 'USD',
            'BN' => 'BND',
            'BG' => 'BGN',
            'BF' => 'XOF',
            'BI' => 'BIF',
            'KH' => 'KHR',
            'CM' => 'XAF',
            'CA' => 'CAD',
            'CV' => 'CVE',
            'KY' => 'KYD',
            'CF' => 'XAF',
            'TD' => 'XAF',
            'CL' => 'CLP',
            'CN' => 'CNY',
            'HK' => 'HKD',
            'CX' => 'AUD',
            'CC' => 'AUD',
            'CO' => 'COP',
            'KM' => 'KMF',
            'CG' => 'XAF',
            'CD' => 'CDF',
            'CK' => 'NZD',
            'CR' => 'CRC',
            'HR' => 'HRK',
            'CU' => 'CUP',
            'CY' => 'EUR',
            'CZ' => 'CZK',
            'DK' => 'DKK',
            'DJ' => 'DJF',
            'DM' => 'XCD',
            'DO' => 'DOP',
            'EC' => 'ECS',
            'EG' => 'EGP',
            'SV' => 'SVC',
            'GQ' => 'XAF',
            'ER' => 'ERN',
            'EE' => 'EUR',
            'ET' => 'ETB',
            'FK' => 'FKP',
            'FO' => 'DKK',
            'FJ' => 'FJD',
            'FI' => 'EUR',
            'FR' => 'EUR',
            'GF' => 'EUR',
            'TF' => 'EUR',
            'GA' => 'XAF',
            'GM' => 'GMD',
            'GE' => 'GEL',
            'DE' => 'EUR',
            'GH' => 'GHS',
            'GI' => 'GIP',
            'GR' => 'EUR',
            'GL' => 'DKK',
            'GD' => 'XCD',
            'GP' => 'EUR',
            'GU' => 'USD',
            'GT' => 'QTQ',
            'GG' => 'GGP',
            'GN' => 'GNF',
            'GW' => 'GWP',
            'GY' => 'GYD',
            'HT' => 'HTG',
            'HM' => 'AUD',
            'HN' => 'HNL',
            'HU' => 'HUF',
            'IS' => 'ISK',
            'IN' => 'INR',
            'ID' => 'IDR',
            'IR' => 'IRR',
            'IQ' => 'IQD',
            'IE' => 'EUR',
            'IM' => 'GBP',
            'IL' => 'ILS',
            'IT' => 'EUR',
            'JM' => 'JMD',
            'JP' => 'JPY',
            'JE' => 'GBP',
            'JO' => 'JOD',
            'KZ' => 'KZT',
            'KE' => 'KES',
            'KI' => 'AUD',
            'KP' => 'KPW',
            'KR' => 'KRW',
            'KW' => 'KWD',
            'KG' => 'KGS',
            'LA' => 'LAK',
            'LV' => 'EUR',
            'LB' => 'LBP',
            'LS' => 'LSL',
            'LR' => 'LRD',
            'LY' => 'LYD',
            'LI' => 'CHF',
            'LT' => 'EUR',
            'LU' => 'EUR',
            'MK' => 'MKD',
            'MG' => 'MGF',
            'MW' => 'MWK',
            'MY' => 'MYR',
            'MV' => 'MVR',
            'ML' => 'XOF',
            'MT' => 'EUR',
            'MH' => 'USD',
            'MQ' => 'EUR',
            'MR' => 'MRO',
            'MU' => 'MUR',
            'YT' => 'EUR',
            'MX' => 'MXN',
            'FM' => 'USD',
            'MD' => 'MDL',
            'MC' => 'EUR',
            'MN' => 'MNT',
            'ME' => 'EUR',
            'MS' => 'XCD',
            'MA' => 'MAD',
            'MZ' => 'MZN',
            'MM' => 'MMK',
            'NA' => 'NAD',
            'NR' => 'AUD',
            'NP' => 'NPR',
            'NL' => 'EUR',
            'AN' => 'ANG',
            'NC' => 'XPF',
            'NZ' => 'NZD',
            'NI' => 'NIO',
            'NE' => 'XOF',
            'NG' => 'NGN',
            'NU' => 'NZD',
            'NF' => 'AUD',
            'MP' => 'USD',
            'NO' => 'NOK',
            'OM' => 'OMR',
            'PK' => 'PKR',
            'PW' => 'USD',
            'PA' => 'PAB',
            'PG' => 'PGK',
            'PY' => 'PYG',
            'PE' => 'PEN',
            'PH' => 'PHP',
            'PN' => 'NZD',
            'PL' => 'PLN',
            'PT' => 'EUR',
            'PR' => 'USD',
            'QA' => 'QAR',
            'RE' => 'EUR',
            'RO' => 'RON',
            'RU' => 'RUB',
            'RW' => 'RWF',
            'SH' => 'SHP',
            'KN' => 'XCD',
            'LC' => 'XCD',
            'PM' => 'EUR',
            'VC' => 'XCD',
            'WS' => 'WST',
            'SM' => 'EUR',
            'ST' => 'STD',
            'SA' => 'SAR',
            'SN' => 'XOF',
            'RS' => 'RSD',
            'SC' => 'SCR',
            'SL' => 'SLL',
            'SG' => 'SGD',
            'SK' => 'EUR',
            'SI' => 'EUR',
            'SB' => 'SBD',
            'SO' => 'SOS',
            'ZA' => 'ZAR',
            'GS' => 'GBP',
            'SS' => 'SSP',
            'ES' => 'EUR',
            'LK' => 'LKR',
            'SD' => 'SDG',
            'SR' => 'SRD',
            'SJ' => 'NOK',
            'SZ' => 'SZL',
            'SE' => 'SEK',
            'CH' => 'CHF',
            'SY' => 'SYP',
            'TW' => 'TWD',
            'TJ' => 'TJS',
            'TZ' => 'TZS',
            'TH' => 'THB',
            'TG' => 'XOF',
            'TK' => 'NZD',
            'TO' => 'TOP',
            'TT' => 'TTD',
            'TN' => 'TND',
            'TR' => 'TRY',
            'TM' => 'TMT',
            'TC' => 'USD',
            'TV' => 'AUD',
            'UG' => 'UGX',
            'UA' => 'UAH',
            'AE' => 'AED',
            'GB' => 'GBP',
            'US' => 'USD',
            'UM' => 'USD',
            'UY' => 'UYU',
            'UZ' => 'UZS',
            'VU' => 'VUV',
            'VE' => 'VEF',
            'VN' => 'VND',
            'VI' => 'USD',
            'WF' => 'XPF',
            'EH' => 'MAD',
            'YE' => 'YER',
            'ZM' => 'ZMW',
            'ZW' => 'ZWD',
        );

        foreach ($currency as $country => $cur) {
            if ($ctry == $country) {
                return $cur;
            }
        }





    }


    public function select_timezone()
    {
        echo '
        <select id="time_zone" name="time_zone" class="form-control">
            
                                            <option value="">Select TimeZone</option>
  <option value="Africa/Abidjan">Africa/Abidjan GMT+0:00</option>
  <option value="Africa/Accra">Africa/Accra GMT+0:00</option>
  <option value="Africa/Addis_Ababa">Africa/Addis_Ababa GMT+3:00</option>
  <option value="Africa/Algiers">Africa/Algiers GMT+1:00</option>
  <option value="Africa/Asmara">Africa/Asmara GMT+3:00</option>
  <option value="Africa/Asmera">Africa/Asmera GMT+3:00</option>
  <option value="Africa/Bamako">Africa/Bamako GMT+0:00</option>
  <option value="Africa/Bangui">Africa/Bangui GMT+1:00</option>
  <option value="Africa/Banjul">Africa/Banjul GMT+0:00</option>
  <option value="Africa/Bissau">Africa/Bissau GMT+0:00</option>
  <option value="Africa/Blantyre">Africa/Blantyre GMT+2:00</option>
  <option value="Africa/Brazzaville">Africa/Brazzaville GMT+1:00</option>
  <option value="Africa/Bujumbura">Africa/Bujumbura GMT+2:00</option>
  <option value="Africa/Cairo">Africa/Cairo GMT+2:00</option>
  <option value="Africa/Casablanca">Africa/Casablanca GMT+0:00</option>
  <option value="Africa/Ceuta">Africa/Ceuta GMT+1:00</option>
  <option value="Africa/Conakry">Africa/Conakry GMT+0:00</option>
  <option value="Africa/Dakar">Africa/Dakar GMT+0:00</option>
  <option value="Africa/Dar_es_Salaam">Africa/Dar_es_Salaam GMT+3:00</option>
  <option value="Africa/Djibouti">Africa/Djibouti GMT+3:00</option>
  <option value="Africa/Douala">Africa/Douala GMT+1:00</option>
  <option value="Africa/El_Aaiun">Africa/El_Aaiun GMT+0:00</option>
  <option value="Africa/Freetown">Africa/Freetown GMT+0:00</option>
  <option value="Africa/Gaborone">Africa/Gaborone GMT+2:00</option>
  <option value="Africa/Harare">Africa/Harare GMT+2:00</option>
  <option value="Africa/Johannesburg">Africa/Johannesburg GMT+2:00</option>
  <option value="Africa/Juba">Africa/Juba GMT+3:00</option>
  <option value="Africa/Kampala">Africa/Kampala GMT+3:00</option>
  <option value="Africa/Khartoum">Africa/Khartoum GMT+2:00</option>
  <option value="Africa/Kigali">Africa/Kigali GMT+2:00</option>
  <option value="Africa/Kinshasa">Africa/Kinshasa GMT+1:00</option>
  <option value="Africa/Lagos">Africa/Lagos GMT+1:00</option>
  <option value="Africa/Libreville">Africa/Libreville GMT+1:00</option>
  <option value="Africa/Lome">Africa/Lome GMT+0:00</option>
  <option value="Africa/Luanda">Africa/Luanda GMT+1:00</option>
  <option value="Africa/Lubumbashi">Africa/Lubumbashi GMT+2:00</option>
  <option value="Africa/Lusaka">Africa/Lusaka GMT+2:00</option>
  <option value="Africa/Malabo">Africa/Malabo GMT+1:00</option>
  <option value="Africa/Maputo">Africa/Maputo GMT+2:00</option>
  <option value="Africa/Maseru">Africa/Maseru GMT+2:00</option>
  <option value="Africa/Mbabane">Africa/Mbabane GMT+2:00</option>
  <option value="Africa/Mogadishu">Africa/Mogadishu GMT+3:00</option>
  <option value="Africa/Monrovia">Africa/Monrovia GMT+0:00</option>
  <option value="Africa/Nairobi">Africa/Nairobi GMT+3:00</option>
  <option value="Africa/Ndjamena">Africa/Ndjamena GMT+1:00</option>
  <option value="Africa/Niamey">Africa/Niamey GMT+1:00</option>
  <option value="Africa/Nouakchott">Africa/Nouakchott GMT+0:00</option>
  <option value="Africa/Ouagadougou">Africa/Ouagadougou GMT+0:00</option>
  <option value="Africa/Porto-Novo">Africa/Porto-Novo GMT+1:00</option>
  <option value="Africa/Sao_Tome">Africa/Sao_Tome GMT+0:00</option>
  <option value="Africa/Timbuktu">Africa/Timbuktu GMT+0:00</option>
  <option value="Africa/Tripoli">Africa/Tripoli GMT+2:00</option>
  <option value="Africa/Tunis">Africa/Tunis GMT+1:00</option>
  <option value="Africa/Windhoek">Africa/Windhoek GMT+2:00</option>
  <option value="America/Adak">America/Adak GMT-10:00</option>
  <option value="America/Anchorage">America/Anchorage GMT-9:00</option>
  <option value="America/Anguilla">America/Anguilla GMT-4:00</option>
  <option value="America/Antigua">America/Antigua GMT-4:00</option>
  <option value="America/Araguaina">America/Araguaina GMT-3:00</option>
  <option value="America/Argentina/Buenos_Aires">America/Argentina/Buenos_Aires GMT-3:00</option>
  <option value="America/Argentina/Catamarca">America/Argentina/Catamarca GMT-3:00</option>
  <option value="America/Argentina/ComodRivadavia">America/Argentina/ComodRivadavia GMT-3:00</option>
  <option value="America/Argentina/Cordoba">America/Argentina/Cordoba GMT-3:00</option>
  <option value="America/Argentina/Jujuy">America/Argentina/Jujuy GMT-3:00</option>
  <option value="America/Argentina/La_Rioja">America/Argentina/La_Rioja GMT-3:00</option>
  <option value="America/Argentina/Mendoza">America/Argentina/Mendoza GMT-3:00</option>
  <option value="America/Argentina/Rio_Gallegos">America/Argentina/Rio_Gallegos GMT-3:00</option>
  <option value="America/Argentina/Salta">America/Argentina/Salta GMT-3:00</option>
  <option value="America/Argentina/San_Juan">America/Argentina/San_Juan GMT-3:00</option>
  <option value="America/Argentina/San_Luis">America/Argentina/San_Luis GMT-3:00</option>
  <option value="America/Argentina/Tucuman">America/Argentina/Tucuman GMT-3:00</option>
  <option value="America/Argentina/Ushuaia">America/Argentina/Ushuaia GMT-3:00</option>
  <option value="America/Aruba">America/Aruba GMT-4:00</option>
  <option value="America/Asuncion">America/Asuncion GMT-4:00</option>
  <option value="America/Atikokan">America/Atikokan GMT-5:00</option>
  <option value="America/Atka">America/Atka GMT-10:00</option>
  <option value="America/Bahia">America/Bahia GMT-3:00</option>
  <option value="America/Bahia_Banderas">America/Bahia_Banderas GMT-6:00</option>
  <option value="America/Barbados">America/Barbados GMT-4:00</option>
  <option value="America/Belem">America/Belem GMT-3:00</option>
  <option value="America/Belize">America/Belize GMT-6:00</option>
  <option value="America/Blanc-Sablon">America/Blanc-Sablon GMT-4:00</option>
  <option value="America/Boa_Vista">America/Boa_Vista GMT-4:00</option>
  <option value="America/Bogota">America/Bogota GMT-5:00</option>
  <option value="America/Boise">America/Boise GMT-7:00</option>
  <option value="America/Buenos_Aires">America/Buenos_Aires GMT-3:00</option>
  <option value="America/Cambridge_Bay">America/Cambridge_Bay GMT-7:00</option>
  <option value="America/Campo_Grande">America/Campo_Grande GMT-4:00</option>
  <option value="America/Cancun">America/Cancun GMT-5:00</option>
  <option value="America/Caracas">America/Caracas GMT-4:00</option>
  <option value="America/Catamarca">America/Catamarca GMT-3:00</option>
  <option value="America/Cayenne">America/Cayenne GMT-3:00</option>
  <option value="America/Cayman">America/Cayman GMT-5:00</option>
  <option value="America/Chicago">America/Chicago GMT-6:00</option>
  <option value="America/Chihuahua">America/Chihuahua GMT-7:00</option>
  <option value="America/Coral_Harbour">America/Coral_Harbour GMT-5:00</option>
  <option value="America/Cordoba">America/Cordoba GMT-3:00</option>
  <option value="America/Costa_Rica">America/Costa_Rica GMT-6:00</option>
  <option value="America/Creston">America/Creston GMT-7:00</option>
  <option value="America/Cuiaba">America/Cuiaba GMT-4:00</option>
  <option value="America/Curacao">America/Curacao GMT-4:00</option>
  <option value="America/Danmarkshavn">America/Danmarkshavn GMT+0:00</option>
  <option value="America/Dawson">America/Dawson GMT-8:00</option>
  <option value="America/Dawson_Creek">America/Dawson_Creek GMT-7:00</option>
  <option value="America/Denver">America/Denver GMT-7:00</option>
  <option value="America/Detroit">America/Detroit GMT-5:00</option>
  <option value="America/Dominica">America/Dominica GMT-4:00</option>
  <option value="America/Edmonton">America/Edmonton GMT-7:00</option>
  <option value="America/Eirunepe">America/Eirunepe GMT-5:00</option>
  <option value="America/El_Salvador">America/El_Salvador GMT-6:00</option>
  <option value="America/Ensenada">America/Ensenada GMT-8:00</option>
  <option value="America/Fort_Nelson">America/Fort_Nelson GMT-7:00</option>
  <option value="America/Fort_Wayne">America/Fort_Wayne GMT-5:00</option>
  <option value="America/Fortaleza">America/Fortaleza GMT-3:00</option>
  <option value="America/Glace_Bay">America/Glace_Bay GMT-4:00</option>
  <option value="America/Godthab">America/Godthab GMT-3:00</option>
  <option value="America/Goose_Bay">America/Goose_Bay GMT-4:00</option>
  <option value="America/Grand_Turk">America/Grand_Turk GMT-5:00</option>
  <option value="America/Grenada">America/Grenada GMT-4:00</option>
  <option value="America/Guadeloupe">America/Guadeloupe GMT-4:00</option>
  <option value="America/Guatemala">America/Guatemala GMT-6:00</option>
  <option value="America/Guayaquil">America/Guayaquil GMT-5:00</option>
  <option value="America/Guyana">America/Guyana GMT-4:00</option>
  <option value="America/Halifax">America/Halifax GMT-4:00</option>
  <option value="America/Havana">America/Havana GMT-5:00</option>
  <option value="America/Hermosillo">America/Hermosillo GMT-7:00</option>
  <option value="America/Indiana/Indianapolis">America/Indiana/Indianapolis GMT-5:00</option>
  <option value="America/Indiana/Knox">America/Indiana/Knox GMT-6:00</option>
  <option value="America/Indiana/Marengo">America/Indiana/Marengo GMT-5:00</option>
  <option value="America/Indiana/Petersburg">America/Indiana/Petersburg GMT-5:00</option>
  <option value="America/Indiana/Tell_City">America/Indiana/Tell_City GMT-6:00</option>
  <option value="America/Indiana/Vevay">America/Indiana/Vevay GMT-5:00</option>
  <option value="America/Indiana/Vincennes">America/Indiana/Vincennes GMT-5:00</option>
  <option value="America/Indiana/Winamac">America/Indiana/Winamac GMT-5:00</option>
  <option value="America/Indianapolis">America/Indianapolis GMT-5:00</option>
  <option value="America/Inuvik">America/Inuvik GMT-7:00</option>
  <option value="America/Iqaluit">America/Iqaluit GMT-5:00</option>
  <option value="America/Jamaica">America/Jamaica GMT-5:00</option>
  <option value="America/Jujuy">America/Jujuy GMT-3:00</option>
  <option value="America/Juneau">America/Juneau GMT-9:00</option>
  <option value="America/Kentucky/Louisville">America/Kentucky/Louisville GMT-5:00</option>
  <option value="America/Kentucky/Monticello">America/Kentucky/Monticello GMT-5:00</option>
  <option value="America/Knox_IN">America/Knox_IN GMT-6:00</option>
  <option value="America/Kralendijk">America/Kralendijk GMT-4:00</option>
  <option value="America/La_Paz">America/La_Paz GMT-4:00</option>
  <option value="America/Lima">America/Lima GMT-5:00</option>
  <option value="America/Los_Angeles">America/Los_Angeles GMT-8:00</option>
  <option value="America/Louisville">America/Louisville GMT-5:00</option>
  <option value="America/Lower_Princes">America/Lower_Princes GMT-4:00</option>
  <option value="America/Maceio">America/Maceio GMT-3:00</option>
  <option value="America/Managua">America/Managua GMT-6:00</option>
  <option value="America/Manaus">America/Manaus GMT-4:00</option>
  <option value="America/Marigot">America/Marigot GMT-4:00</option>
  <option value="America/Martinique">America/Martinique GMT-4:00</option>
  <option value="America/Matamoros">America/Matamoros GMT-6:00</option>
  <option value="America/Mazatlan">America/Mazatlan GMT-7:00</option>
  <option value="America/Mendoza">America/Mendoza GMT-3:00</option>
  <option value="America/Menominee">America/Menominee GMT-6:00</option>
  <option value="America/Merida">America/Merida GMT-6:00</option>
  <option value="America/Metlakatla">America/Metlakatla GMT-9:00</option>
  <option value="America/Mexico_City">America/Mexico_City GMT-6:00</option>
  <option value="America/Miquelon">America/Miquelon GMT-3:00</option>
  <option value="America/Moncton">America/Moncton GMT-4:00</option>
  <option value="America/Monterrey">America/Monterrey GMT-6:00</option>
  <option value="America/Montevideo">America/Montevideo GMT-3:00</option>
  <option value="America/Montreal">America/Montreal GMT-5:00</option>
  <option value="America/Montserrat">America/Montserrat GMT-4:00</option>
  <option value="America/Nassau">America/Nassau GMT-5:00</option>
  <option value="America/New_York">America/New_York GMT-5:00</option>
  <option value="America/Nipigon">America/Nipigon GMT-5:00</option>
  <option value="America/Nome">America/Nome GMT-9:00</option>
  <option value="America/Noronha">America/Noronha GMT-2:00</option>
  <option value="America/North_Dakota/Beulah">America/North_Dakota/Beulah GMT-6:00</option>
  <option value="America/North_Dakota/Center">America/North_Dakota/Center GMT-6:00</option>
  <option value="America/North_Dakota/New_Salem">America/North_Dakota/New_Salem GMT-6:00</option>
  <option value="America/Ojinaga">America/Ojinaga GMT-7:00</option>
  <option value="America/Panama">America/Panama GMT-5:00</option>
  <option value="America/Pangnirtung">America/Pangnirtung GMT-5:00</option>
  <option value="America/Paramaribo">America/Paramaribo GMT-3:00</option>
  <option value="America/Phoenix">America/Phoenix GMT-7:00</option>
  <option value="America/Port-au-Prince">America/Port-au-Prince GMT-5:00</option>
  <option value="America/Port_of_Spain">America/Port_of_Spain GMT-4:00</option>
  <option value="America/Porto_Acre">America/Porto_Acre GMT-5:00</option>
  <option value="America/Porto_Velho">America/Porto_Velho GMT-4:00</option>
  <option value="America/Puerto_Rico">America/Puerto_Rico GMT-4:00</option>
  <option value="America/Punta_Arenas">America/Punta_Arenas GMT-3:00</option>
  <option value="America/Rainy_River">America/Rainy_River GMT-6:00</option>
  <option value="America/Rankin_Inlet">America/Rankin_Inlet GMT-6:00</option>
  <option value="America/Recife">America/Recife GMT-3:00</option>
  <option value="America/Regina">America/Regina GMT-6:00</option>
  <option value="America/Resolute">America/Resolute GMT-6:00</option>
  <option value="America/Rio_Branco">America/Rio_Branco GMT-5:00</option>
  <option value="America/Rosario">America/Rosario GMT-3:00</option>
  <option value="America/Santa_Isabel">America/Santa_Isabel GMT-8:00</option>
  <option value="America/Santarem">America/Santarem GMT-3:00</option>
  <option value="America/Santiago">America/Santiago GMT-4:00</option>
  <option value="America/Santo_Domingo">America/Santo_Domingo GMT-4:00</option>
  <option value="America/Sao_Paulo">America/Sao_Paulo GMT-3:00</option>
  <option value="America/Scoresbysund">America/Scoresbysund GMT-1:00</option>
  <option value="America/Shiprock">America/Shiprock GMT-7:00</option>
  <option value="America/Sitka">America/Sitka GMT-9:00</option>
  <option value="America/St_Barthelemy">America/St_Barthelemy GMT-4:00</option>
  <option value="America/St_Johns">America/St_Johns GMT-4:30</option>
  <option value="America/St_Kitts">America/St_Kitts GMT-4:00</option>
  <option value="America/St_Lucia">America/St_Lucia GMT-4:00</option>
  <option value="America/St_Thomas">America/St_Thomas GMT-4:00</option>
  <option value="America/St_Vincent">America/St_Vincent GMT-4:00</option>
  <option value="America/Swift_Current">America/Swift_Current GMT-6:00</option>
  <option value="America/Tegucigalpa">America/Tegucigalpa GMT-6:00</option>
  <option value="America/Thule">America/Thule GMT-4:00</option>
  <option value="America/Thunder_Bay">America/Thunder_Bay GMT-5:00</option>
  <option value="America/Tijuana">America/Tijuana GMT-8:00</option>
  <option value="America/Toronto">America/Toronto GMT-5:00</option>
  <option value="America/Tortola">America/Tortola GMT-4:00</option>
  <option value="America/Vancouver">America/Vancouver GMT-8:00</option>
  <option value="America/Virgin">America/Virgin GMT-4:00</option>
  <option value="America/Whitehorse">America/Whitehorse GMT-8:00</option>
  <option value="America/Winnipeg">America/Winnipeg GMT-6:00</option>
  <option value="America/Yakutat">America/Yakutat GMT-9:00</option>
  <option value="America/Yellowknife">America/Yellowknife GMT-7:00</option>
  <option value="Antarctica/Casey">Antarctica/Casey GMT+8:00</option>
  <option value="Antarctica/Davis">Antarctica/Davis GMT+7:00</option>
  <option value="Antarctica/DumontDUrville">Antarctica/DumontDUrville GMT+10:00</option>
  <option value="Antarctica/Macquarie">Antarctica/Macquarie GMT+11:00</option>
  <option value="Antarctica/Mawson">Antarctica/Mawson GMT+5:00</option>
  <option value="Antarctica/McMurdo">Antarctica/McMurdo GMT+12:00</option>
  <option value="Antarctica/Palmer">Antarctica/Palmer GMT-3:00</option>
  <option value="Antarctica/Rothera">Antarctica/Rothera GMT-3:00</option>
  <option value="Antarctica/South_Pole">Antarctica/South_Pole GMT+12:00</option>
  <option value="Antarctica/Syowa">Antarctica/Syowa GMT+3:00</option>
  <option value="Antarctica/Troll">Antarctica/Troll GMT+0:00</option>
  <option value="Antarctica/Vostok">Antarctica/Vostok GMT+6:00</option>
  <option value="Arctic/Longyearbyen">Arctic/Longyearbyen GMT+1:00</option>
  <option value="Asia/Aden">Asia/Aden GMT+3:00</option>
  <option value="Asia/Almaty">Asia/Almaty GMT+6:00</option>
  <option value="Asia/Amman">Asia/Amman GMT+2:00</option>
  <option value="Asia/Anadyr">Asia/Anadyr GMT+12:00</option>
  <option value="Asia/Aqtau">Asia/Aqtau GMT+5:00</option>
  <option value="Asia/Aqtobe">Asia/Aqtobe GMT+5:00</option>
  <option value="Asia/Ashgabat">Asia/Ashgabat GMT+5:00</option>
  <option value="Asia/Ashkhabad">Asia/Ashkhabad GMT+5:00</option>
  <option value="Asia/Atyrau">Asia/Atyrau GMT+5:00</option>
  <option value="Asia/Baghdad">Asia/Baghdad GMT+3:00</option>
  <option value="Asia/Bahrain">Asia/Bahrain GMT+3:00</option>
  <option value="Asia/Baku">Asia/Baku GMT+4:00</option>
  <option value="Asia/Bangkok">Asia/Bangkok GMT+7:00</option>
  <option value="Asia/Barnaul">Asia/Barnaul GMT+7:00</option>
  <option value="Asia/Beirut">Asia/Beirut GMT+2:00</option>
  <option value="Asia/Bishkek">Asia/Bishkek GMT+6:00</option>
  <option value="Asia/Brunei">Asia/Brunei GMT+8:00</option>
  <option value="Asia/Calcutta">Asia/Calcutta GMT+5:30</option>
  <option value="Asia/Chita">Asia/Chita GMT+9:00</option>
  <option value="Asia/Choibalsan">Asia/Choibalsan GMT+8:00</option>
  <option value="Asia/Chongqing">Asia/Chongqing GMT+8:00</option>
  <option value="Asia/Chungking">Asia/Chungking GMT+8:00</option>
  <option value="Asia/Colombo">Asia/Colombo GMT+5:30</option>
  <option value="Asia/Dacca">Asia/Dacca GMT+6:00</option>
  <option value="Asia/Damascus">Asia/Damascus GMT+2:00</option>
  <option value="Asia/Dhaka">Asia/Dhaka GMT+6:00</option>
  <option value="Asia/Dili">Asia/Dili GMT+9:00</option>
  <option value="Asia/Dubai">Asia/Dubai GMT+4:00</option>
  <option value="Asia/Dushanbe">Asia/Dushanbe GMT+5:00</option>
  <option value="Asia/Famagusta">Asia/Famagusta GMT+2:00</option>
  <option value="Asia/Gaza">Asia/Gaza GMT+2:00</option>
  <option value="Asia/Harbin">Asia/Harbin GMT+8:00</option>
  <option value="Asia/Hebron">Asia/Hebron GMT+2:00</option>
  <option value="Asia/Ho_Chi_Minh">Asia/Ho_Chi_Minh GMT+7:00</option>
  <option value="Asia/Hong_Kong">Asia/Hong_Kong GMT+8:00</option>
  <option value="Asia/Hovd">Asia/Hovd GMT+7:00</option>
  <option value="Asia/Irkutsk">Asia/Irkutsk GMT+8:00</option>
  <option value="Asia/Istanbul">Asia/Istanbul GMT+3:00</option>
  <option value="Asia/Jakarta">Asia/Jakarta GMT+7:00</option>
  <option value="Asia/Jayapura">Asia/Jayapura GMT+9:00</option>
  <option value="Asia/Jerusalem">Asia/Jerusalem GMT+2:00</option>
  <option value="Asia/Kabul">Asia/Kabul GMT+4:30</option>
  <option value="Asia/Kamchatka">Asia/Kamchatka GMT+12:00</option>
  <option value="Asia/Karachi">Asia/Karachi GMT+5:00</option>
  <option value="Asia/Kashgar">Asia/Kashgar GMT+6:00</option>
  <option value="Asia/Kathmandu">Asia/Kathmandu GMT+5:45</option>
  <option value="Asia/Katmandu">Asia/Katmandu GMT+5:45</option>
  <option value="Asia/Khandyga">Asia/Khandyga GMT+9:00</option>
  <option value="Asia/Kolkata">Asia/Kolkata GMT+5:30</option>
  <option value="Asia/Krasnoyarsk">Asia/Krasnoyarsk GMT+7:00</option>
  <option value="Asia/Kuala_Lumpur">Asia/Kuala_Lumpur GMT+8:00</option>
  <option value="Asia/Kuching">Asia/Kuching GMT+8:00</option>
  <option value="Asia/Kuwait">Asia/Kuwait GMT+3:00</option>
  <option value="Asia/Macao">Asia/Macao GMT+8:00</option>
  <option value="Asia/Macau">Asia/Macau GMT+8:00</option>
  <option value="Asia/Magadan">Asia/Magadan GMT+11:00</option>
  <option value="Asia/Makassar">Asia/Makassar GMT+8:00</option>
  <option value="Asia/Manila">Asia/Manila GMT+8:00</option>
  <option value="Asia/Muscat">Asia/Muscat GMT+4:00</option>
  <option value="Asia/Nicosia">Asia/Nicosia GMT+2:00</option>
  <option value="Asia/Novokuznetsk">Asia/Novokuznetsk GMT+7:00</option>
  <option value="Asia/Novosibirsk">Asia/Novosibirsk GMT+7:00</option>
  <option value="Asia/Omsk">Asia/Omsk GMT+6:00</option>
  <option value="Asia/Oral">Asia/Oral GMT+5:00</option>
  <option value="Asia/Phnom_Penh">Asia/Phnom_Penh GMT+7:00</option>
  <option value="Asia/Pontianak">Asia/Pontianak GMT+7:00</option>
  <option value="Asia/Pyongyang">Asia/Pyongyang GMT+9:00</option>
  <option value="Asia/Qatar">Asia/Qatar GMT+3:00</option>
  <option value="Asia/Qostanay">Asia/Qostanay GMT+6:00</option>
  <option value="Asia/Qyzylorda">Asia/Qyzylorda GMT+5:00</option>
  <option value="Asia/Rangoon">Asia/Rangoon GMT+6:30</option>
  <option value="Asia/Riyadh">Asia/Riyadh GMT+3:00</option>
  <option value="Asia/Saigon">Asia/Saigon GMT+7:00</option>
  <option value="Asia/Sakhalin">Asia/Sakhalin GMT+11:00</option>
  <option value="Asia/Samarkand">Asia/Samarkand GMT+5:00</option>
  <option value="Asia/Seoul">Asia/Seoul GMT+9:00</option>
  <option value="Asia/Shanghai">Asia/Shanghai GMT+8:00</option>
  <option value="Asia/Singapore">Asia/Singapore GMT+8:00</option>
  <option value="Asia/Srednekolymsk">Asia/Srednekolymsk GMT+11:00</option>
  <option value="Asia/Taipei">Asia/Taipei GMT+8:00</option>
  <option value="Asia/Tashkent">Asia/Tashkent GMT+5:00</option>
  <option value="Asia/Tbilisi">Asia/Tbilisi GMT+4:00</option>
  <option value="Asia/Tehran">Asia/Tehran GMT+3:30</option>
  <option value="Asia/Tel_Aviv">Asia/Tel_Aviv GMT+2:00</option>
  <option value="Asia/Thimbu">Asia/Thimbu GMT+6:00</option>
  <option value="Asia/Thimphu">Asia/Thimphu GMT+6:00</option>
  <option value="Asia/Tokyo">Asia/Tokyo GMT+9:00</option>
  <option value="Asia/Tomsk">Asia/Tomsk GMT+7:00</option>
  <option value="Asia/Ujung_Pandang">Asia/Ujung_Pandang GMT+8:00</option>
  <option value="Asia/Ulaanbaatar">Asia/Ulaanbaatar GMT+8:00</option>
  <option value="Asia/Ulan_Bator">Asia/Ulan_Bator GMT+8:00</option>
  <option value="Asia/Urumqi">Asia/Urumqi GMT+6:00</option>
  <option value="Asia/Ust-Nera">Asia/Ust-Nera GMT+10:00</option>
  <option value="Asia/Vientiane">Asia/Vientiane GMT+7:00</option>
  <option value="Asia/Vladivostok">Asia/Vladivostok GMT+10:00</option>
  <option value="Asia/Yakutsk">Asia/Yakutsk GMT+9:00</option>
  <option value="Asia/Yangon">Asia/Yangon GMT+6:30</option>
  <option value="Asia/Yekaterinburg">Asia/Yekaterinburg GMT+5:00</option>
  <option value="Asia/Yerevan">Asia/Yerevan GMT+4:00</option>
  <option value="Atlantic/Azores">Atlantic/Azores GMT-1:00</option>
  <option value="Atlantic/Bermuda">Atlantic/Bermuda GMT-4:00</option>
  <option value="Atlantic/Canary">Atlantic/Canary GMT+0:00</option>
  <option value="Atlantic/Cape_Verde">Atlantic/Cape_Verde GMT-1:00</option>
  <option value="Atlantic/Faeroe">Atlantic/Faeroe GMT+0:00</option>
  <option value="Atlantic/Faroe">Atlantic/Faroe GMT+0:00</option>
  <option value="Atlantic/Jan_Mayen">Atlantic/Jan_Mayen GMT+1:00</option>
  <option value="Atlantic/Madeira">Atlantic/Madeira GMT+0:00</option>
  <option value="Atlantic/Reykjavik">Atlantic/Reykjavik GMT+0:00</option>
  <option value="Atlantic/South_Georgia">Atlantic/South_Georgia GMT-2:00</option>
  <option value="Atlantic/St_Helena">Atlantic/St_Helena GMT+0:00</option>
  <option value="Atlantic/Stanley">Atlantic/Stanley GMT-3:00</option>
  <option value="Australia/ACT">Australia/ACT GMT+10:00</option>
  <option value="Australia/Adelaide">Australia/Adelaide GMT+9:30</option>
  <option value="Australia/Brisbane">Australia/Brisbane GMT+10:00</option>
  <option value="Australia/Broken_Hill">Australia/Broken_Hill GMT+9:30</option>
  <option value="Australia/Canberra">Australia/Canberra GMT+10:00</option>
  <option value="Australia/Currie">Australia/Currie GMT+10:00</option>
  <option value="Australia/Darwin">Australia/Darwin GMT+9:30</option>
  <option value="Australia/Eucla">Australia/Eucla GMT+8:45</option>
  <option value="Australia/Hobart">Australia/Hobart GMT+10:00</option>
  <option value="Australia/LHI">Australia/LHI GMT+10:30</option>
  <option value="Australia/Lindeman">Australia/Lindeman GMT+10:00</option>
  <option value="Australia/Lord_Howe">Australia/Lord_Howe GMT+10:30</option>
  <option value="Australia/Melbourne">Australia/Melbourne GMT+10:00</option>
  <option value="Australia/NSW">Australia/NSW GMT+10:00</option>
  <option value="Australia/North">Australia/North GMT+9:30</option>
  <option value="Australia/Perth">Australia/Perth GMT+8:00</option>
  <option value="Australia/Queensland">Australia/Queensland GMT+10:00</option>
  <option value="Australia/South">Australia/South GMT+9:30</option>
  <option value="Australia/Sydney">Australia/Sydney GMT+10:00</option>
  <option value="Australia/Tasmania">Australia/Tasmania GMT+10:00</option>
  <option value="Australia/Victoria">Australia/Victoria GMT+10:00</option>
  <option value="Australia/West">Australia/West GMT+8:00</option>
  <option value="Australia/Yancowinna">Australia/Yancowinna GMT+9:30</option>
  <option value="Brazil/Acre">Brazil/Acre GMT-5:00</option>
  <option value="Brazil/DeNoronha">Brazil/DeNoronha GMT-2:00</option>
  <option value="Brazil/East">Brazil/East GMT-3:00</option>
  <option value="Brazil/West">Brazil/West GMT-4:00</option>
  <option value="CET">CET GMT+1:00</option>
  <option value="CST6CDT">CST6CDT GMT-6:00</option>
  <option value="Canada/Atlantic">Canada/Atlantic GMT-4:00</option>
  <option value="Canada/Central">Canada/Central GMT-6:00</option>
  <option value="Canada/Eastern">Canada/Eastern GMT-5:00</option>
  <option value="Canada/Mountain">Canada/Mountain GMT-7:00</option>
  <option value="Canada/Newfoundland">Canada/Newfoundland GMT-4:30</option>
  <option value="Canada/Pacific">Canada/Pacific GMT-8:00</option>
  <option value="Canada/Saskatchewan">Canada/Saskatchewan GMT-6:00</option>
  <option value="Canada/Yukon">Canada/Yukon GMT-8:00</option>
  <option value="Chile/Continental">Chile/Continental GMT-4:00</option>
  <option value="Chile/EasterIsland">Chile/EasterIsland GMT-6:00</option>
  <option value="Cuba">Cuba GMT-5:00</option>
  <option value="EET">EET GMT+2:00</option>
  <option value="EST5EDT">EST5EDT GMT-5:00</option>
  <option value="Egypt">Egypt GMT+2:00</option>
  <option value="Eire">Eire GMT+0:00</option>
  <option value="Etc/GMT">Etc/GMT GMT+0:00</option>
  <option value="Etc/GMT+0">Etc/GMT+0 GMT+0:00</option>
  <option value="Etc/GMT+1">Etc/GMT+1 GMT-1:00</option>
  <option value="Etc/GMT+10">Etc/GMT+10 GMT-10:00</option>
  <option value="Etc/GMT+11">Etc/GMT+11 GMT-11:00</option>
  <option value="Etc/GMT+12">Etc/GMT+12 GMT-12:00</option>
  <option value="Etc/GMT+2">Etc/GMT+2 GMT-2:00</option>
  <option value="Etc/GMT+3">Etc/GMT+3 GMT-3:00</option>
  <option value="Etc/GMT+4">Etc/GMT+4 GMT-4:00</option>
  <option value="Etc/GMT+5">Etc/GMT+5 GMT-5:00</option>
  <option value="Etc/GMT+6">Etc/GMT+6 GMT-6:00</option>
  <option value="Etc/GMT+7">Etc/GMT+7 GMT-7:00</option>
  <option value="Etc/GMT+8">Etc/GMT+8 GMT-8:00</option>
  <option value="Etc/GMT+9">Etc/GMT+9 GMT-9:00</option>
  <option value="Etc/GMT-0">Etc/GMT-0 GMT+0:00</option>
  <option value="Etc/GMT-1">Etc/GMT-1 GMT+1:00</option>
  <option value="Etc/GMT-10">Etc/GMT-10 GMT+10:00</option>
  <option value="Etc/GMT-11">Etc/GMT-11 GMT+11:00</option>
  <option value="Etc/GMT-12">Etc/GMT-12 GMT+12:00</option>
  <option value="Etc/GMT-13">Etc/GMT-13 GMT+13:00</option>
  <option value="Etc/GMT-14">Etc/GMT-14 GMT+14:00</option>
  <option value="Etc/GMT-2">Etc/GMT-2 GMT+2:00</option>
  <option value="Etc/GMT-3">Etc/GMT-3 GMT+3:00</option>
  <option value="Etc/GMT-4">Etc/GMT-4 GMT+4:00</option>
  <option value="Etc/GMT-5">Etc/GMT-5 GMT+5:00</option>
  <option value="Etc/GMT-6">Etc/GMT-6 GMT+6:00</option>
  <option value="Etc/GMT-7">Etc/GMT-7 GMT+7:00</option>
  <option value="Etc/GMT-8">Etc/GMT-8 GMT+8:00</option>
  <option value="Etc/GMT-9">Etc/GMT-9 GMT+9:00</option>
  <option value="Etc/GMT0">Etc/GMT0 GMT+0:00</option>
  <option value="Etc/Greenwich">Etc/Greenwich GMT+0:00</option>
  <option value="Etc/UCT">Etc/UCT GMT+0:00</option>
  <option value="Etc/UTC">Etc/UTC GMT+0:00</option>
  <option value="Etc/Universal">Etc/Universal GMT+0:00</option>
  <option value="Etc/Zulu">Etc/Zulu GMT+0:00</option>
  <option value="Europe/Amsterdam">Europe/Amsterdam GMT+1:00</option>
  <option value="Europe/Andorra">Europe/Andorra GMT+1:00</option>
  <option value="Europe/Astrakhan">Europe/Astrakhan GMT+4:00</option>
  <option value="Europe/Athens">Europe/Athens GMT+2:00</option>
  <option value="Europe/Belfast">Europe/Belfast GMT+0:00</option>
  <option value="Europe/Belgrade">Europe/Belgrade GMT+1:00</option>
  <option value="Europe/Berlin">Europe/Berlin GMT+1:00</option>
  <option value="Europe/Bratislava">Europe/Bratislava GMT+1:00</option>
  <option value="Europe/Brussels">Europe/Brussels GMT+1:00</option>
  <option value="Europe/Bucharest">Europe/Bucharest GMT+2:00</option>
  <option value="Europe/Budapest">Europe/Budapest GMT+1:00</option>
  <option value="Europe/Busingen">Europe/Busingen GMT+1:00</option>
  <option value="Europe/Chisinau">Europe/Chisinau GMT+2:00</option>
  <option value="Europe/Copenhagen">Europe/Copenhagen GMT+1:00</option>
  <option value="Europe/Dublin">Europe/Dublin GMT+0:00</option>
  <option value="Europe/Gibraltar">Europe/Gibraltar GMT+1:00</option>
  <option value="Europe/Guernsey">Europe/Guernsey GMT+0:00</option>
  <option value="Europe/Helsinki">Europe/Helsinki GMT+2:00</option>
  <option value="Europe/Isle_of_Man">Europe/Isle_of_Man GMT+0:00</option>
  <option value="Europe/Istanbul">Europe/Istanbul GMT+3:00</option>
  <option value="Europe/Jersey">Europe/Jersey GMT+0:00</option>
  <option value="Europe/Kaliningrad">Europe/Kaliningrad GMT+2:00</option>
  <option value="Europe/Kiev">Europe/Kiev GMT+2:00</option>
  <option value="Europe/Kirov">Europe/Kirov GMT+3:00</option>
  <option value="Europe/Lisbon">Europe/Lisbon GMT+0:00</option>
  <option value="Europe/Ljubljana">Europe/Ljubljana GMT+1:00</option>
  <option value="Europe/London">Europe/London GMT+0:00</option>
  <option value="Europe/Luxembourg">Europe/Luxembourg GMT+1:00</option>
  <option value="Europe/Madrid">Europe/Madrid GMT+1:00</option>
  <option value="Europe/Malta">Europe/Malta GMT+1:00</option>
  <option value="Europe/Mariehamn">Europe/Mariehamn GMT+2:00</option>
  <option value="Europe/Minsk">Europe/Minsk GMT+3:00</option>
  <option value="Europe/Monaco">Europe/Monaco GMT+1:00</option>
  <option value="Europe/Moscow">Europe/Moscow GMT+3:00</option>
  <option value="Europe/Nicosia">Europe/Nicosia GMT+2:00</option>
  <option value="Europe/Oslo">Europe/Oslo GMT+1:00</option>
  <option value="Europe/Paris">Europe/Paris GMT+1:00</option>
  <option value="Europe/Podgorica">Europe/Podgorica GMT+1:00</option>
  <option value="Europe/Prague">Europe/Prague GMT+1:00</option>
  <option value="Europe/Riga">Europe/Riga GMT+2:00</option>
  <option value="Europe/Rome">Europe/Rome GMT+1:00</option>
  <option value="Europe/Samara">Europe/Samara GMT+4:00</option>
  <option value="Europe/San_Marino">Europe/San_Marino GMT+1:00</option>
  <option value="Europe/Sarajevo">Europe/Sarajevo GMT+1:00</option>
  <option value="Europe/Saratov">Europe/Saratov GMT+4:00</option>
  <option value="Europe/Simferopol">Europe/Simferopol GMT+3:00</option>
  <option value="Europe/Skopje">Europe/Skopje GMT+1:00</option>
  <option value="Europe/Sofia">Europe/Sofia GMT+2:00</option>
  <option value="Europe/Stockholm">Europe/Stockholm GMT+1:00</option>
  <option value="Europe/Tallinn">Europe/Tallinn GMT+2:00</option>
  <option value="Europe/Tirane">Europe/Tirane GMT+1:00</option>
  <option value="Europe/Tiraspol">Europe/Tiraspol GMT+2:00</option>
  <option value="Europe/Ulyanovsk">Europe/Ulyanovsk GMT+4:00</option>
  <option value="Europe/Uzhgorod">Europe/Uzhgorod GMT+2:00</option>
  <option value="Europe/Vaduz">Europe/Vaduz GMT+1:00</option>
  <option value="Europe/Vatican">Europe/Vatican GMT+1:00</option>
  <option value="Europe/Vienna">Europe/Vienna GMT+1:00</option>
  <option value="Europe/Vilnius">Europe/Vilnius GMT+2:00</option>
  <option value="Europe/Volgograd">Europe/Volgograd GMT+4:00</option>
  <option value="Europe/Warsaw">Europe/Warsaw GMT+1:00</option>
  <option value="Europe/Zagreb">Europe/Zagreb GMT+1:00</option>
  <option value="Europe/Zaporozhye">Europe/Zaporozhye GMT+2:00</option>
  <option value="Europe/Zurich">Europe/Zurich GMT+1:00</option>
  <option value="GB">GB GMT+0:00</option>
  <option value="GB-Eire">GB-Eire GMT+0:00</option>
  <option value="GMT">GMT GMT+0:00</option>
  <option value="GMT0">GMT0 GMT+0:00</option>
  <option value="Greenwich">Greenwich GMT+0:00</option>
  <option value="Hongkong">Hongkong GMT+8:00</option>
  <option value="Iceland">Iceland GMT+0:00</option>
  <option value="Indian/Antananarivo">Indian/Antananarivo GMT+3:00</option>
  <option value="Indian/Chagos">Indian/Chagos GMT+6:00</option>
  <option value="Indian/Christmas">Indian/Christmas GMT+7:00</option>
  <option value="Indian/Cocos">Indian/Cocos GMT+6:30</option>
  <option value="Indian/Comoro">Indian/Comoro GMT+3:00</option>
  <option value="Indian/Kerguelen">Indian/Kerguelen GMT+5:00</option>
  <option value="Indian/Mahe">Indian/Mahe GMT+4:00</option>
  <option value="Indian/Maldives">Indian/Maldives GMT+5:00</option>
  <option value="Indian/Mauritius">Indian/Mauritius GMT+4:00</option>
  <option value="Indian/Mayotte">Indian/Mayotte GMT+3:00</option>
  <option value="Indian/Reunion">Indian/Reunion GMT+4:00</option>
  <option value="Iran">Iran GMT+3:30</option>
  <option value="Israel">Israel GMT+2:00</option>
  <option value="Jamaica">Jamaica GMT-5:00</option>
  <option value="Japan">Japan GMT+9:00</option>
  <option value="Kwajalein">Kwajalein GMT+12:00</option>
  <option value="Libya">Libya GMT+2:00</option>
  <option value="MET">MET GMT+1:00</option>
  <option value="MST7MDT">MST7MDT GMT-7:00</option>
  <option value="Mexico/BajaNorte">Mexico/BajaNorte GMT-8:00</option>
  <option value="Mexico/BajaSur">Mexico/BajaSur GMT-7:00</option>
  <option value="Mexico/General">Mexico/General GMT-6:00</option>
  <option value="NZ">NZ GMT+12:00</option>
  <option value="NZ-CHAT">NZ-CHAT GMT+12:45</option>
  <option value="Navajo">Navajo GMT-7:00</option>
  <option value="PRC">PRC GMT+8:00</option>
  <option value="PST8PDT">PST8PDT GMT-8:00</option>
  <option value="Pacific/Apia">Pacific/Apia GMT+13:00</option>
  <option value="Pacific/Auckland">Pacific/Auckland GMT+12:00</option>
  <option value="Pacific/Bougainville">Pacific/Bougainville GMT+11:00</option>
  <option value="Pacific/Chatham">Pacific/Chatham GMT+12:45</option>
  <option value="Pacific/Chuuk">Pacific/Chuuk GMT+10:00</option>
  <option value="Pacific/Easter">Pacific/Easter GMT-6:00</option>
  <option value="Pacific/Efate">Pacific/Efate GMT+11:00</option>
  <option value="Pacific/Enderbury">Pacific/Enderbury GMT+13:00</option>
  <option value="Pacific/Fakaofo">Pacific/Fakaofo GMT+13:00</option>
  <option value="Pacific/Fiji">Pacific/Fiji GMT+12:00</option>
  <option value="Pacific/Funafuti">Pacific/Funafuti GMT+12:00</option>
  <option value="Pacific/Galapagos">Pacific/Galapagos GMT-6:00</option>
  <option value="Pacific/Gambier">Pacific/Gambier GMT-9:00</option>
  <option value="Pacific/Guadalcanal">Pacific/Guadalcanal GMT+11:00</option>
  <option value="Pacific/Guam">Pacific/Guam GMT+10:00</option>
  <option value="Pacific/Honolulu">Pacific/Honolulu GMT-10:00</option>
  <option value="Pacific/Johnston">Pacific/Johnston GMT-10:00</option>
  <option value="Pacific/Kiritimati">Pacific/Kiritimati GMT+14:00</option>
  <option value="Pacific/Kosrae">Pacific/Kosrae GMT+11:00</option>
  <option value="Pacific/Kwajalein">Pacific/Kwajalein GMT+12:00</option>
  <option value="Pacific/Majuro">Pacific/Majuro GMT+12:00</option>
  <option value="Pacific/Marquesas">Pacific/Marquesas GMT-10:30</option>
  <option value="Pacific/Midway">Pacific/Midway GMT-11:00</option>
  <option value="Pacific/Nauru">Pacific/Nauru GMT+12:00</option>
  <option value="Pacific/Niue">Pacific/Niue GMT-11:00</option>
  <option value="Pacific/Norfolk">Pacific/Norfolk GMT+11:00</option>
  <option value="Pacific/Noumea">Pacific/Noumea GMT+11:00</option>
  <option value="Pacific/Pago_Pago">Pacific/Pago_Pago GMT-11:00</option>
  <option value="Pacific/Palau">Pacific/Palau GMT+9:00</option>
  <option value="Pacific/Pitcairn">Pacific/Pitcairn GMT-8:00</option>
  <option value="Pacific/Pohnpei">Pacific/Pohnpei GMT+11:00</option>
  <option value="Pacific/Ponape">Pacific/Ponape GMT+11:00</option>
  <option value="Pacific/Port_Moresby">Pacific/Port_Moresby GMT+10:00</option>
  <option value="Pacific/Rarotonga">Pacific/Rarotonga GMT-10:00</option>
  <option value="Pacific/Saipan">Pacific/Saipan GMT+10:00</option>
  <option value="Pacific/Samoa">Pacific/Samoa GMT-11:00</option>
  <option value="Pacific/Tahiti">Pacific/Tahiti GMT-10:00</option>
  <option value="Pacific/Tarawa">Pacific/Tarawa GMT+12:00</option>
  <option value="Pacific/Tongatapu">Pacific/Tongatapu GMT+13:00</option>
  <option value="Pacific/Truk">Pacific/Truk GMT+10:00</option>
  <option value="Pacific/Wake">Pacific/Wake GMT+12:00</option>
  <option value="Pacific/Wallis">Pacific/Wallis GMT+12:00</option>
  <option value="Pacific/Yap">Pacific/Yap GMT+10:00</option>
  <option value="Poland">Poland GMT+1:00</option>
  <option value="Portugal">Portugal GMT+0:00</option>
  <option value="ROK">ROK GMT+9:00</option>
  <option value="Singapore">Singapore GMT+8:00</option>
  <option value="SystemV/AST4">SystemV/AST4 GMT-4:00</option>
  <option value="SystemV/AST4ADT">SystemV/AST4ADT GMT-4:00</option>
  <option value="SystemV/CST6">SystemV/CST6 GMT-6:00</option>
  <option value="SystemV/CST6CDT">SystemV/CST6CDT GMT-6:00</option>
  <option value="SystemV/EST5">SystemV/EST5 GMT-5:00</option>
  <option value="SystemV/EST5EDT">SystemV/EST5EDT GMT-5:00</option>
  <option value="SystemV/HST10">SystemV/HST10 GMT-10:00</option>
  <option value="SystemV/MST7">SystemV/MST7 GMT-7:00</option>
  <option value="SystemV/MST7MDT">SystemV/MST7MDT GMT-7:00</option>
  <option value="SystemV/PST8">SystemV/PST8 GMT-8:00</option>
  <option value="SystemV/PST8PDT">SystemV/PST8PDT GMT-8:00</option>
  <option value="SystemV/YST9">SystemV/YST9 GMT-9:00</option>
  <option value="SystemV/YST9YDT">SystemV/YST9YDT GMT-9:00</option>
  <option value="Turkey">Turkey GMT+3:00</option>
  <option value="UCT">UCT GMT+0:00</option>
  <option value="US/Alaska">US/Alaska GMT-9:00</option>
  <option value="US/Aleutian">US/Aleutian GMT-10:00</option>
  <option value="US/Arizona">US/Arizona GMT-7:00</option>
  <option value="US/Central">US/Central GMT-6:00</option>
  <option value="US/East-Indiana">US/East-Indiana GMT-5:00</option>
  <option value="US/Eastern">US/Eastern GMT-5:00</option>
  <option value="US/Hawaii">US/Hawaii GMT-10:00</option>
  <option value="US/Indiana-Starke">US/Indiana-Starke GMT-6:00</option>
  <option value="US/Michigan">US/Michigan GMT-5:00</option>
  <option value="US/Mountain">US/Mountain GMT-7:00</option>
  <option value="US/Pacific">US/Pacific GMT-8:00</option>
  <option value="US/Pacific-New">US/Pacific-New GMT-8:00</option>
  <option value="US/Samoa">US/Samoa GMT-11:00</option>
  <option value="UTC">UTC GMT+0:00</option>
  <option value="Universal">Universal GMT+0:00</option>
  <option value="W-SU">W-SU GMT+3:00</option>
  <option value="WET">WET GMT+0:00</option>
  <option value="Zulu">Zulu GMT+0:00</option>
  <option value="EST">EST GMT-5:00</option>
  <option value="HST">HST GMT-10:00</option>
  <option value="MST">MST GMT-7:00</option>
  <option value="ACT">ACT GMT+9:30</option>
  <option value="AET">AET GMT+10:00</option>
  <option value="AGT">AGT GMT-3:00</option>
  <option value="ART">ART GMT+2:00</option>
  <option value="AST">AST GMT-9:00</option>
  <option value="BET">BET GMT-3:00</option>
  <option value="BST">BST GMT+6:00</option>
  <option value="CAT">CAT GMT+2:00</option>
  <option value="CNT">CNT GMT-4:30</option>
  <option value="CST">CST GMT-6:00</option>
  <option value="CTT">CTT GMT+8:00</option>
  <option value="EAT">EAT GMT+3:00</option>
  <option value="ECT">ECT GMT+1:00</option>
  <option value="IET">IET GMT-5:00</option>
  <option value="IST">IST GMT+5:30</option>
  <option value="JST">JST GMT+9:00</option>
  <option value="MIT">MIT GMT+13:00</option>
  <option value="NET">NET GMT+4:00</option>
  <option value="NST">NST GMT+12:00</option>
  <option value="PLT">PLT GMT+5:00</option>
  <option value="PNT">PNT GMT-7:00</option>
  <option value="PRT">PRT GMT-4:00</option>
  <option value="PST">PST GMT-8:00</option>
  <option value="SST">SST GMT+11:00</option>
  <option value="VST">VST GMT+7:00</option>
</select>
        ';
    }

    // country and currencies as a associative array
    public function select_currency()
    {
        echo
            '
        <select class="form-select" id="currency" name="currency">
    <option>select currency</option>
    <option value="AFN">Afghan Afghani</option>
    <option value="ALL">Albanian Lek</option>
    <option value="DZD">Algerian Dinar</option>
    <option value="AOA">Angolan Kwanza</option>
    <option value="ARS">Argentine Peso</option>
    <option value="AMD">Armenian Dram</option>
    <option value="AWG">Aruban Florin</option>
    <option value="AUD">Australian Dollar</option>
    <option value="AZN">Azerbaijani Manat</option>
    <option value="BSD">Bahamian Dollar</option>
    <option value="BHD">Bahraini Dinar</option>
    <option value="BDT">Bangladeshi Taka</option>
    <option value="BBD">Barbadian Dollar</option>
    <option value="BYR">Belarusian Ruble</option>
    <option value="BEF">Belgian Franc</option>
    <option value="BZD">Belize Dollar</option>
    <option value="BMD">Bermudan Dollar</option>
    <option value="BTN">Bhutanese Ngultrum</option>
    <option value="BTC">Bitcoin</option>
    <option value="BOB">Bolivian Boliviano</option>
    <option value="BAM">Bosnia-Herzegovina Convertible Mark</option>
    <option value="BWP">Botswanan Pula</option>
    <option value="BRL">Brazilian Real</option>
    <option value="GBP">British Pound Sterling</option>
    <option value="BND">Brunei Dollar</option>
    <option value="BGN">Bulgarian Lev</option>
    <option value="BIF">Burundian Franc</option>
    <option value="KHR">Cambodian Riel</option>
    <option value="CAD">Canadian Dollar</option>
    <option value="CVE">Cape Verdean Escudo</option>
    <option value="KYD">Cayman Islands Dollar</option>
    <option value="XOF">CFA Franc BCEAO</option>
    <option value="XAF">CFA Franc BEAC</option>
    <option value="XPF">CFP Franc</option>
    <option value="CLP">Chilean Peso</option>
    <option value="CNY">Chinese Yuan</option>
    <option value="COP">Colombian Peso</option>
    <option value="KMF">Comorian Franc</option>
    <option value="CDF">Congolese Franc</option>
    <option value="CRC">Costa Rican ColÃ³n</option>
    <option value="HRK">Croatian Kuna</option>
    <option value="CUC">Cuban Convertible Peso</option>
    <option value="CZK">Czech Republic Koruna</option>
    <option value="DKK">Danish Krone</option>
    <option value="DJF">Djiboutian Franc</option>
    <option value="DOP">Dominican Peso</option>
    <option value="XCD">East Caribbean Dollar</option>
    <option value="EGP">Egyptian Pound</option>
    <option value="ERN">Eritrean Nakfa</option>
    <option value="EEK">Estonian Kroon</option>
    <option value="ETB">Ethiopian Birr</option>
    <option value="EUR">Euro</option>
    <option value="FKP">Falkland Islands Pound</option>
    <option value="FJD">Fijian Dollar</option>
    <option value="GMD">Gambian Dalasi</option>
    <option value="GEL">Georgian Lari</option>
    <option value="DEM">German Mark</option>
    <option value="GHS">Ghanaian Cedi</option>
    <option value="GIP">Gibraltar Pound</option>
    <option value="GRD">Greek Drachma</option>
    <option value="GTQ">Guatemalan Quetzal</option>
    <option value="GNF">Guinean Franc</option>
    <option value="GYD">Guyanaese Dollar</option>
    <option value="HTG">Haitian Gourde</option>
    <option value="HNL">Honduran Lempira</option>
    <option value="HKD">Hong Kong Dollar</option>
    <option value="HUF">Hungarian Forint</option>
    <option value="ISK">Icelandic KrÃ³na</option>
    <option value="INR">Indian Rupee</option>
    <option value="IDR">Indonesian Rupiah</option>
    <option value="IRR">Iranian Rial</option>
    <option value="IQD">Iraqi Dinar</option>
    <option value="ILS">Israeli New Sheqel</option>
    <option value="ITL">Italian Lira</option>
    <option value="JMD">Jamaican Dollar</option>
    <option value="JPY">Japanese Yen</option>
    <option value="JOD">Jordanian Dinar</option>
    <option value="KZT">Kazakhstani Tenge</option>
    <option value="KES">Kenyan Shilling</option>
    <option value="KWD">Kuwaiti Dinar</option>
    <option value="KGS">Kyrgystani Som</option>
    <option value="LAK">Laotian Kip</option>
    <option value="LVL">Latvian Lats</option>
    <option value="LBP">Lebanese Pound</option>
    <option value="LSL">Lesotho Loti</option>
    <option value="LRD">Liberian Dollar</option>
    <option value="LYD">Libyan Dinar</option>
    <option value="LTL">Lithuanian Litas</option>
    <option value="MOP">Macanese Pataca</option>
    <option value="MKD">Macedonian Denar</option>
    <option value="MGA">Malagasy Ariary</option>
    <option value="MWK">Malawian Kwacha</option>
    <option value="MYR">Malaysian Ringgit</option>
    <option value="MVR">Maldivian Rufiyaa</option>
    <option value="MRO">Mauritanian Ouguiya</option>
    <option value="MUR">Mauritian Rupee</option>
    <option value="MXN">Mexican Peso</option>
    <option value="MDL">Moldovan Leu</option>
    <option value="MNT">Mongolian Tugrik</option>
    <option value="MAD">Moroccan Dirham</option>
    <option value="MZM">Mozambican Metical</option>
    <option value="MMK">Myanmar Kyat</option>
    <option value="NAD">Namibian Dollar</option>
    <option value="NPR">Nepalese Rupee</option>
    <option value="ANG">Netherlands Antillean Guilder</option>
    <option value="TWD">New Taiwan Dollar</option>
    <option value="NZD">New Zealand Dollar</option>
    <option value="NIO">Nicaraguan CÃ³rdoba</option>
    <option value="NGN">Nigerian Naira</option>
    <option value="KPW">North Korean Won</option>
    <option value="NOK">Norwegian Krone</option>
    <option value="OMR">Omani Rial</option>
    <option value="PKR">Pakistani Rupee</option>
    <option value="PAB">Panamanian Balboa</option>
    <option value="PGK">Papua New Guinean Kina</option>
    <option value="PYG">Paraguayan Guarani</option>
    <option value="PEN">Peruvian Nuevo Sol</option>
    <option value="PHP">Philippine Peso</option>
    <option value="PLN">Polish Zloty</option>
    <option value="QAR">Qatari Rial</option>
    <option value="RON">Romanian Leu</option>
    <option value="RUB">Russian Ruble</option>
    <option value="RWF">Rwandan Franc</option>
    <option value="SVC">Salvadoran ColÃ³n</option>
    <option value="WST">Samoan Tala</option>
    <option value="SAR">Saudi Riyal</option>
    <option value="RSD">Serbian Dinar</option>
    <option value="SCR">Seychellois Rupee</option>
    <option value="SLL">Sierra Leonean Leone</option>
    <option value="SGD">Singapore Dollar</option>
    <option value="SKK">Slovak Koruna</option>
    <option value="SBD">Solomon Islands Dollar</option>
    <option value="SOS">Somali Shilling</option>
    <option value="ZAR">South African Rand</option>
    <option value="KRW">South Korean Won</option>
    <option value="XDR">Special Drawing Rights</option>
    <option value="LKR">Sri Lankan Rupee</option>
    <option value="SHP">St. Helena Pound</option>
    <option value="SDG">Sudanese Pound</option>
    <option value="SRD">Surinamese Dollar</option>
    <option value="SZL">Swazi Lilangeni</option>
    <option value="SEK">Swedish Krona</option>
    <option value="CHF">Swiss Franc</option>
    <option value="SYP">Syrian Pound</option>
    <option value="STD">São Tomé and Príncipe Dobra</option>
    <option value="TJS">Tajikistani Somoni</option>
    <option value="TZS">Tanzanian Shilling</option>
    <option value="THB">Thai Baht</option>
    <option value="TOP">Tongan paanga</option>
    <option value="TTD">Trinidad & Tobago Dollar</option>
    <option value="TND">Tunisian Dinar</option>
    <option value="TRY">Turkish Lira</option>
    <option value="TMT">Turkmenistani Manat</option>
    <option value="UGX">Ugandan Shilling</option>
    <option value="UAH">Ukrainian Hryvnia</option>
    <option value="AED">United Arab Emirates Dirham</option>
    <option value="UYU">Uruguayan Peso</option>
    <option value="USD">US Dollar</option>
    <option value="UZS">Uzbekistan Som</option>
    <option value="VUV">Vanuatu Vatu</option>
    <option value="VEF">Venezuelan BolÃ­var</option>
    <option value="VND">Vietnamese Dong</option>
    <option value="YER">Yemeni Rial</option>
    <option value="ZMK">Zambian Kwacha</option>
</select>
        ';
    }

    public function select_countries()
    {
        echo
            '
        <select class="form-select" id="country" name="country">
    <option>select country</option>
    <option value="AF">Afghanistan</option>
    <option value="AX">Aland Islands</option>
    <option value="AL">Albania</option>
    <option value="DZ">Algeria</option>
    <option value="AS">American Samoa</option>
    <option value="AD">Andorra</option>
    <option value="AO">Angola</option>
    <option value="AI">Anguilla</option>
    <option value="AQ">Antarctica</option>
    <option value="AG">Antigua and Barbuda</option>
    <option value="AR">Argentina</option>
    <option value="AM">Armenia</option>
    <option value="AW">Aruba</option>
    <option value="AU">Australia</option>
    <option value="AT">Austria</option>
    <option value="AZ">Azerbaijan</option>
    <option value="BS">Bahamas</option>
    <option value="BH">Bahrain</option>
    <option value="BD">Bangladesh</option>
    <option value="BB">Barbados</option>
    <option value="BY">Belarus</option>
    <option value="BE">Belgium</option>
    <option value="BZ">Belize</option>
    <option value="BJ">Benin</option>
    <option value="BM">Bermuda</option>
    <option value="BT">Bhutan</option>
    <option value="BO">Bolivia</option>
    <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
    <option value="BA">Bosnia and Herzegovina</option>
    <option value="BW">Botswana</option>
    <option value="BV">Bouvet Island</option>
    <option value="BR">Brazil</option>
    <option value="IO">British Indian Ocean Territory</option>
    <option value="BN">Brunei Darussalam</option>
    <option value="BG">Bulgaria</option>
    <option value="BF">Burkina Faso</option>
    <option value="BI">Burundi</option>
    <option value="KH">Cambodia</option>
    <option value="CM">Cameroon</option>
    <option value="CA">Canada</option>
    <option value="CV">Cape Verde</option>
    <option value="KY">Cayman Islands</option>
    <option value="CF">Central African Republic</option>
    <option value="TD">Chad</option>
    <option value="CL">Chile</option>
    <option value="CN">China</option>
    <option value="CX">Christmas Island</option>
    <option value="CC">Cocos (Keeling) Islands</option>
    <option value="CO">Colombia</option>
    <option value="KM">Comoros</option>
    <option value="CG">Congo</option>
    <option value="CD">Congo, Democratic Republic of the Congo</option>
    <option value="CK">Cook Islands</option>
    <option value="CR">Costa Rica</option>
    <option value="CI">Cote DIvoire</option>
    <option value="HR">Croatia</option>
    <option value="CU">Cuba</option>
    <option value="CW">Curacao</option>
    <option value="CY">Cyprus</option>
    <option value="CZ">Czech Republic</option>
    <option value="DK">Denmark</option>
    <option value="DJ">Djibouti</option>
    <option value="DM">Dominica</option>
    <option value="DO">Dominican Republic</option>
    <option value="EC">Ecuador</option>
    <option value="EG">Egypt</option>
    <option value="SV">El Salvador</option>
    <option value="GQ">Equatorial Guinea</option>
    <option value="ER">Eritrea</option>
    <option value="EE">Estonia</option>
    <option value="ET">Ethiopia</option>
    <option value="FK">Falkland Islands (Malvinas)</option>
    <option value="FO">Faroe Islands</option>
    <option value="FJ">Fiji</option>
    <option value="FI">Finland</option>
    <option value="FR">France</option>
    <option value="GF">French Guiana</option>
    <option value="PF">French Polynesia</option>
    <option value="TF">French Southern Territories</option>
    <option value="GA">Gabon</option>
    <option value="GM">Gambia</option>
    <option value="GE">Georgia</option>
    <option value="DE">Germany</option>
    <option value="GH">Ghana</option>
    <option value="GI">Gibraltar</option>
    <option value="GR">Greece</option>
    <option value="GL">Greenland</option>
    <option value="GD">Grenada</option>
    <option value="GP">Guadeloupe</option>
    <option value="GU">Guam</option>
    <option value="GT">Guatemala</option>
    <option value="GG">Guernsey</option>
    <option value="GN">Guinea</option>
    <option value="GW">Guinea-Bissau</option>
    <option value="GY">Guyana</option>
    <option value="HT">Haiti</option>
    <option value="HM">Heard Island and Mcdonald Islands</option>
    <option value="VA">Holy See (Vatican City State)</option>
    <option value="HN">Honduras</option>
    <option value="HK">Hong Kong</option>
    <option value="HU">Hungary</option>
    <option value="IS">Iceland</option>
    <option value="IN">India</option>
    <option value="ID">Indonesia</option>
    <option value="IR">Iran, Islamic Republic of</option>
    <option value="IQ">Iraq</option>
    <option value="IE">Ireland</option>
    <option value="IM">Isle of Man</option>
    <option value="IL">Israel</option>
    <option value="IT">Italy</option>
    <option value="JM">Jamaica</option>
    <option value="JP">Japan</option>
    <option value="JE">Jersey</option>
    <option value="JO">Jordan</option>
    <option value="KZ">Kazakhstan</option>
    <option value="KE">Kenya</option>
    <option value="KI">Kiribati</option>
    <option value="KP">Korea, Democratic People</option>
    <option value="KR">Korea, Republic of</option>
    <option value="XK">Kosovo</option>
    <option value="KW">Kuwait</option>
    <option value="KG">Kyrgyzstan</option>
    <option value="LA">Lao Democratic Republic</option>
    <option value="LV">Latvia</option>
    <option value="LB">Lebanon</option>
    <option value="LS">Lesotho</option>
    <option value="LR">Liberia</option>
    <option value="LY">Libyan Arab Jamahiriya</option>
    <option value="LI">Liechtenstein</option>
    <option value="LT">Lithuania</option>
    <option value="LU">Luxembourg</option>
    <option value="MO">Macao</option>
    <option value="MK">Macedonia, the Former Yugoslav Republic of</option>
    <option value="MG">Madagascar</option>
    <option value="MW">Malawi</option>
    <option value="MY">Malaysia</option>
    <option value="MV">Maldives</option>
    <option value="ML">Mali</option>
    <option value="MT">Malta</option>
    <option value="MH">Marshall Islands</option>
    <option value="MQ">Martinique</option>
    <option value="MR">Mauritania</option>
    <option value="MU">Mauritius</option>
    <option value="YT">Mayotte</option>
    <option value="MX">Mexico</option>
    <option value="FM">Micronesia, Federated States of</option>
    <option value="MD">Moldova, Republic of</option>
    <option value="MC">Monaco</option>
    <option value="MN">Mongolia</option>
    <option value="ME">Montenegro</option>
    <option value="MS">Montserrat</option>
    <option value="MA">Morocco</option>
    <option value="MZ">Mozambique</option>
    <option value="MM">Myanmar</option>
    <option value="NA">Namibia</option>
    <option value="NR">Nauru</option>
    <option value="NP">Nepal</option>
    <option value="NL">Netherlands</option>
    <option value="AN">Netherlands Antilles</option>
    <option value="NC">New Caledonia</option>
    <option value="NZ">New Zealand</option>
    <option value="NI">Nicaragua</option>
    <option value="NE">Niger</option>
    <option value="NG">Nigeria</option>
    <option value="NU">Niue</option>
    <option value="NF">Norfolk Island</option>
    <option value="MP">Northern Mariana Islands</option>
    <option value="NO">Norway</option>
    <option value="OM">Oman</option>
    <option value="PK">Pakistan</option>
    <option value="PW">Palau</option>
    <option value="PS">Palestinian Territory, Occupied</option>
    <option value="PA">Panama</option>
    <option value="PG">Papua New Guinea</option>
    <option value="PY">Paraguay</option>
    <option value="PE">Peru</option>
    <option value="PH">Philippines</option>
    <option value="PN">Pitcairn</option>
    <option value="PL">Poland</option>
    <option value="PT">Portugal</option>
    <option value="PR">Puerto Rico</option>
    <option value="QA">Qatar</option>
    <option value="RE">Reunion</option>
    <option value="RO">Romania</option>
    <option value="RU">Russian Federation</option>
    <option value="RW">Rwanda</option>
    <option value="BL">Saint Barthelemy</option>
    <option value="SH">Saint Helena</option>
    <option value="KN">Saint Kitts and Nevis</option>
    <option value="LC">Saint Lucia</option>
    <option value="MF">Saint Martin</option>
    <option value="PM">Saint Pierre and Miquelon</option>
    <option value="VC">Saint Vincent and the Grenadines</option>
    <option value="WS">Samoa</option>
    <option value="SM">San Marino</option>
    <option value="ST">Sao Tome and Principe</option>
    <option value="SA">Saudi Arabia</option>
    <option value="SN">Senegal</option>
    <option value="RS">Serbia</option>
    <option value="CS">Serbia and Montenegro</option>
    <option value="SC">Seychelles</option>
    <option value="SL">Sierra Leone</option>
    <option value="SG">Singapore</option>
    <option value="SX">Sint Maarten</option>
    <option value="SK">Slovakia</option>
    <option value="SI">Slovenia</option>
    <option value="SB">Solomon Islands</option>
    <option value="SO">Somalia</option>
    <option value="ZA">South Africa</option>
    <option value="GS">South Georgia and the South Sandwich Islands</option>
    <option value="SS">South Sudan</option>
    <option value="ES">Spain</option>
    <option value="LK">Sri Lanka</option>
    <option value="SD">Sudan</option>
    <option value="SR">Suriname</option>
    <option value="SJ">Svalbard and Jan Mayen</option>
    <option value="SZ">Swaziland</option>
    <option value="SE">Sweden</option>
    <option value="CH">Switzerland</option>
    <option value="SY">Syrian Arab Republic</option>
    <option value="TW">Taiwan, Province of China</option>
    <option value="TJ">Tajikistan</option>
    <option value="TZ">Tanzania, United Republic of</option>
    <option value="TH">Thailand</option>
    <option value="TL">Timor-Leste</option>
    <option value="TG">Togo</option>
    <option value="TK">Tokelau</option>
    <option value="TO">Tonga</option>
    <option value="TT">Trinidad and Tobago</option>
    <option value="TN">Tunisia</option>
    <option value="TR">Turkey</option>
    <option value="TM">Turkmenistan</option>
    <option value="TC">Turks and Caicos Islands</option>
    <option value="TV">Tuvalu</option>
    <option value="UG">Uganda</option>
    <option value="UA">Ukraine</option>
    <option value="AE">United Arab Emirates</option>
    <option value="GB">United Kingdom</option>
    <option value="US">United States</option>
    <option value="UM">United States Minor Outlying Islands</option>
    <option value="UY">Uruguay</option>
    <option value="UZ">Uzbekistan</option>
    <option value="VU">Vanuatu</option>
    <option value="VE">Venezuela</option>
    <option value="VN">Viet Nam</option>
    <option value="VG">Virgin Islands, British</option>
    <option value="VI">Virgin Islands, U.s.</option>
    <option value="WF">Wallis and Futuna</option>
    <option value="EH">Western Sahara</option>
    <option value="YE">Yemen</option>
    <option value="ZM">Zambia</option>
    <option value="ZW">Zimbabwe</option>
</select>
        ';
    }
    */


}