<?php
/**
 * This file is part of the Sclable Xml Lint Package.
 *
 * @copyright (c) 2015 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace sclable\xmlLint\validator;

use sclable\xmlLint\data\FileReport;
use xmlHandler\exception\XMLHandlerException;
use xmlHandler\XMLFileHandler;

/**
 * Class LintValidation
 *
 *
 * @package php\validator
 * @author Michael Rutz <michael.rutz@sclable.com>
 *
 */
class LintValidation implements ValidationInterface
{
    /**
     * @inheritdoc
     */
    public function validateFile(FileReport $report)
    {
        $handler = XMLFileHandler::createXmlHandlerFromFile($report->getFile());

        try {
            $handler->getDOMDocument();
            return true;
        } catch (XMLHandlerException $e) {
            if ($handler->hasErrors()) {
                $problems = [];
                foreach ($handler->getXmlErrors() as $xmlError) {
                    $problems[] = sprintf(
                        'Line %s: [%s] %s',
                        $xmlError->line,
                        $xmlError->code,
                        trim($xmlError->message)
                    );
                };

                foreach (array_unique($problems) as $problem) {
                    $report->reportProblem($problem);
                }

            } else {
                $report->reportProblem(sprintf(
                    '%s (Exception: "%s")' . PHP_EOL,
                    $e->getMessage(),
                    get_class($e)
                ));
            }
        }

        return false;
    }
}
