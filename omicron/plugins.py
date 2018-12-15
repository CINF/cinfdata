import numpy as np

class OverflowFilter(object):
    """Plugin that will filter out the 1E+38 overflow values that are saved if
    the electrometer is saturated in its range.
    """

    def __init__(self, settings, plot_options, ggs=None):
        self.settings = settings
        self.label_additions = {
            'xlabel_addition': '',
            'y_left_label_addition': '',
            'y_right_label_addition': '',
        }

    def run(self, left, right):
        """The main run method. 'left' and 'right' will be the data chosen of
        the left and right axes respectively"""
        message = ''

        # Loop through data sets
        changes = False
        for data_set in left + right:
            old_size = len(data_set['data'])

            # Only apply to current data
            meta = data_set['meta']
            if not meta['mass_label'] in ['electrometer', 'picoammeter']:
                continue

            # Only keep currents below 1 ampere
            data_set['data'] = data_set['data'][ np.where( data_set['data'][:,1] < 1 ) ]
            diff = old_size - len(data_set['data'])

            # Show info if we have changed data set
            if diff > 0:
                changes = True
                print('{0} overflow values removed'.format(diff))

        if changes:
            message = 'Overflow data removed'
            self.label_additions['y_left_label_addition'] = message
            self.label_additions['y_right_label_addition'] = message
        return self.label_additions

class Dummy(object):
    def __init__(self, settings, plot_options, ggs=None):
        self.settings = settings
        self.label_additions = {
            'xlabel_addition': '',
            'y_left_label_addition': '',
            'y_right_label_addition': '',
        }

    def run(self, left, right):
        return sels.label_additions
