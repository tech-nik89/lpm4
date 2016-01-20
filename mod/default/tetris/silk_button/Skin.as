import net.sf.flashlib.util.Delegate;

import net.sf.emff.EMFF;
import net.sf.emff.UpdateUtil;
import net.sf.emff.configuration.ConfigurationFlashVars;



/**
 * The "silk_button" skin.
 * 
 * @version  0.0.1
 * @author   Marc Reichelt
 */
class Skin {
	
	private static var skin : Skin = null;
	
	private var emff : EMFF = null;
	private var playMC : MovieClip = null;
	private var stopMC : MovieClip = null;
	
	/**
	 * Main method that simply creates a new skin.
	 */
	public static function main() : Void {
		
		if (UpdateUtil.getInstance().updateNeeded()) {
			return;
		}
		
		skin = new Skin();
	}
	
	/**
	 * Initialization of this skin.
	 */
	private function Skin() {
		// read configuration and create new player
		emff = new EMFF( new ConfigurationFlashVars() );
		
		playMC = _root.attachMovie("player_play", "playMC", _root.getNextHighestDepth());
		stopMC = _root.attachMovie("player_stop", "stopMC", _root.getNextHighestDepth());
		playMC._x = 1;
		playMC._y = 1;
		stopMC._x = 1;
		stopMC._y = 1;
		
		// set actions
		playMC.onRelease = Delegate.create(emff, emff.play);
		stopMC.onRelease = Delegate.create(emff, emff.stop);
		
		playMC._visible = true;
		stopMC._visible = false;
		
		_root.onEnterFrame = Delegate.create(this, enterFrame);
		
	}
	
	/**
	 * Method that is activated every frame and will refresh the visualisation.
	 */
	private function enterFrame() : Void {
		var status : Number = emff.getStatus();
		
		if (status == EMFF.STOPPED) {
			playMC._visible = true;
			stopMC._visible = false;
		} else {
			playMC._visible = false;
			stopMC._visible = true;
		}
	}
	
}
