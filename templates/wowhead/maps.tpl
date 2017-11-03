{include file='header.tpl'}

		<div id="main">
			<div id="main-precontents"></div>
			<div id="main-contents" class="main-contents">
				<div class="text">
					<div style="text-align: center">
						<script type="text/javascript">g_initPath([1,1])</script>
						<div class="text">
							<div style="text-align: center">
								<select id="maps-ek" onchange="ma_ChooseZone(this)" class="zone-picker" style="margin: 0">
									<option value="0" style="color: #bbbbbb">{#Eastern_Kingdoms#}</option>
								</select>
								<select id="maps-kalimdor" onchange="ma_ChooseZone(this)" class="zone-picker">
									<option value="0" style="color: #bbbbbb">{#Kalimdor#}</option>
								</select>
								<select id="maps-outland" onchange="ma_ChooseZone(this)" class="zone-picker">
									<option value="0" style="color: #bbbbbb">{#Outland#}</option>
								</select>
								<select id="maps-northrend" onchange="ma_ChooseZone(this)" class="zone-picker">
									<option value="0" style="color: #bbbbbb">{#Northrend#}</option>
								</select>
								<div style="padding-bottom: 4px"></div>
								<select onchange="ma_ChooseZone(this)" class="zone-picker">
									<option value="0" style="color: #bbbbbb">{#Instances#}</option>
									<optgroup label="{#Dungeons#}" id="maps-dungeons"></optgroup>
									<optgroup label="{#Raids#}" id="maps-raids"></optgroup>
								</select>
								<select onchange="ma_ChooseZone(this)" class="zone-picker">
									<option value="0" style="color: #bbbbbb">{#More#}</option>
									<optgroup label="{#Battlegrounds#}" id="maps-battlegrounds"></optgroup>
									<optgroup label="{#Miscellaneous#}">
										<option value="-1">{#Azeroth#}</option>
										<option value="-3">{#Eastern_Kingdoms#}</option>
										<option value="457">{#Kalimdor#}</option>
										<option value="-2">{#Outland#}</option>
										<option value="-5">{#Northrend#}</option>
										<option value="-4">{#Cosmic_Map#}</option>
									</optgroup>
								</select>
							</div>
							<div id="mapper" style="display: none; width: 778px; margin: 0 auto">
								<div id="dbs3b53"></div>
								<div class="pad"></div>
								<div style="text-align: center; font-size: 13px">
									<a href="javascript:;" style="margin-right: 2em" id="link-to-this-map">{#Link_to_this_map#}</a>
									<a href="javascript:;" onclick="myMapper.setCoords([])" onmousedown="return false">{#Clear#}</a>
								</div>
							</div>
							<script type="text/javascript">ma_Init();</script>
						</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
		</div>

{include file='footer.tpl'}
